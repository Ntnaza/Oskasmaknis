import { defineStore } from 'pinia';
import api from '@/services/api';
import { useAngkatanStore } from './angkatan'; // <-- 1. IMPORT STORE ANGKATAN

export const useContentBlockStore = defineStore('contentBlock', {
  state: () => ({
    blocks: {},
  }),
  actions: {
    async fetchBlocksByPage(pageSlug) {
      const angkatanStore = useAngkatanStore(); // <-- 2. GUNAKAN STORE

      try {
        // <-- 3. KIRIM PARAMETER ANGKATAN_ID
        // Menggunakan opsi 'params' axios agar lebih rapi daripada string concatenation
        const response = await api.get('/content-blocks', {
            params: {
                page_slug: pageSlug,
                angkatan_id: angkatanStore.selectedId
            }
        });

        const blocksAsObject = response.data.data.reduce((obj, item) => {
          obj[item.section_key] = item;
          return obj;
        }, {});
        this.blocks = blocksAsObject;
      } catch (error) {
        console.error(`Error fetching content blocks for page ${pageSlug}:`, error);
      }
    },
    
    // FUNGSI UPDATE BARU DENGAN KEMAMPUAN UPLOAD
    async updateContentBlocksWithFiles(blocksArray, files) {
      const angkatanStore = useAngkatanStore(); // <-- 4. GUNAKAN STORE
      const formData = new FormData();

      // <-- 5. SISIPKAN ANGKATAN_ID KE FORMDATA
      // Ini wajib agar Backend tahu konten ini milik angkatan mana
      if (angkatanStore.selectedId) {
          formData.append('angkatan_id', angkatanStore.selectedId);
      } else {
          alert("Mohon pilih angkatan terlebih dahulu!");
          throw new Error("Angkatan ID missing");
      }

      // 1. Tambahkan data teks (dalam format string JSON)
      formData.append('blocks', JSON.stringify(blocksArray));

      // 2. Tambahkan file-file (jika ada)
      if (files.osisFile) {
        formData.append('osisFile', files.osisFile); 
      }
      if (files.mpkFile) {
        formData.append('mpkFile', files.mpkFile);
      }
      
      // TAMBAHAN UNTUK PEMBINA
      if (files.pembinaFile) {
        formData.append('pembinaFile', files.pembinaFile);
      }

      try {
        // Kirim sebagai multipart/form-data
        await api.post('/content-blocks/update-bulk', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        
        // Refresh data setelah update agar tampilan UI sinkron
        await this.fetchBlocksByPage('index');
        
      } catch (error) {
        console.error('Error updating content blocks:', error);
        throw error;
      }
    },
  },
});