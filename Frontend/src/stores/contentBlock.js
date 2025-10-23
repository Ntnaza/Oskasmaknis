import { defineStore } from 'pinia';
import api from '@/services/api';

export const useContentBlockStore = defineStore('contentBlock', {
  state: () => ({
    blocks: {},
  }),
  actions: {
    async fetchBlocksByPage(pageSlug) {
      try {
        const response = await api.get(`/content-blocks?page_slug=${pageSlug}`);
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
      const formData = new FormData();

      // 1. Tambahkan data teks (dalam format string JSON)
      formData.append('blocks', JSON.stringify(blocksArray));

      // 2. Tambahkan file-file (jika ada)
      if (files.osisFile) {
        // Nama key 'osisFile' di sini harus cocok dengan $request->hasFile('osisFile') di backend
        formData.append('osisFile', files.osisFile); 
      }
      if (files.mpkFile) {
        // Nama key 'mpkFile' di sini harus cocok dengan $request->hasFile('mpkFile') di backend
        formData.append('mpkFile', files.mpkFile);
      }
      // ======================================================
      // ==           TAMBAHKAN BLOK INI UNTUK PEMBINA         ==
      // ======================================================
      if (files.pembinaFile) {
        // Nama key 'pembinaFile' di sini harus cocok dengan $request->hasFile('pembinaFile') di backend
        formData.append('pembinaFile', files.pembinaFile);
      }
      // ======================================================

      try {
        // Kirim sebagai multipart/form-data
        // Pastikan endpoint '/content-blocks/update-bulk' benar
        await api.post('/content-blocks/update-bulk', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            // Jika API Anda memerlukan metode PUT atau PATCH untuk update,
            // Anda mungkin perlu menambahkan '_method': 'PUT' atau 'PATCH' di FormData
            // formData.append('_method', 'PUT'); 
            // Atau gunakan api.put(...) jika library Anda mendukungnya dengan FormData
          },
        });
      } catch (error) {
        console.error('Error updating content blocks:', error);
        throw error;
      }
    },
  },
});