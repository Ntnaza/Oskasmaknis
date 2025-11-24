<template>
  <form @submit.prevent="saveContent">
    <div class="flex flex-wrap">
      
      <div class="w-full mb-6 mt-6">
        <h6 class="text-blueGray-700 text-xl font-bold">
          Konten Hero (Index Utama)
        </h6>
        <!-- Indikator Angkatan Aktif -->
        <small v-if="angkatanStore.activeAngkatan" class="text-emerald-600 font-bold block mt-1">
            Mengelola untuk: {{ angkatanStore.activeAngkatan.name }}
        </small>
      </div>

      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul Utama</label>
          <input type="text" v-model="content.title" class="form-input-underline" placeholder="Selamat Datang di..." required/>
        </div>
      </div>
      
      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
          <textarea v-model="content.description" class="form-textarea-underline" rows="4" placeholder="Deskripsi singkat..."></textarea>
        </div>
      </div>

      <div class="w-full lg:w-12/12 mt-8">
        <button 
          class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" 
          type="submit"
          :disabled="isLoading"
        >
          <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
          {{ isLoading ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
      </div>

    </div>
  </form>
</template>

<script>
import axios from "axios";
import { useAngkatanStore } from "@/stores/angkatan"; // 1. Import Store

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
const SECTION_KEY = 'index-hero'; // Kunci database untuk bagian ini
const API_URL = `${API_BASE_URL}/api/content-block/${SECTION_KEY}`;

export default {
  data() { 
    return { 
      angkatanStore: useAngkatanStore(), // 2. Init Store
      content: { 
        title: "", 
        description: "", 
      }, 
      isLoading: false
    }; 
  },
  
  // 3. Watcher untuk Auto-Reload saat ganti tahun
  watch: {
    'angkatanStore.selectedId': {
      handler(newVal) {
        if (newVal) {
            this.fetchContent();
        }
      },
      immediate: true 
    }
  },

  methods: { 
    async fetchContent() { 
      if (!this.angkatanStore.selectedId) return;

      this.isLoading = true;
      try { 
        // 4. Kirim params angkatan_id
        const response = await axios.get(API_URL, {
            params: {
                angkatan_id: this.angkatanStore.selectedId
            }
        });
        
        const blockData = response.data.data;

        if (blockData && blockData.content) {
             this.content = {
                 title: blockData.content.title || "",
                 description: blockData.content.description || "",
             };
        } else {
             // Reset jika data belum ada
             this.content = { title: "", description: "" };
        }

      } catch (error) { 
        console.error("Gagal memuat data:", error); 
        this.content = { title: "", description: "" };
      } finally {
        this.isLoading = false;
      }
    }, 
    
    async saveContent() { 
      if (!this.angkatanStore.selectedId) {
          alert("Mohon pilih Angkatan terlebih dahulu di menu atas!");
          return;
      }

      this.isLoading = true;
      const formData = new FormData();
      
      // 5. Wajib kirim angkatan_id & page_slug
      formData.append('angkatan_id', this.angkatanStore.selectedId);
      formData.append('page_slug', 'index'); // Penanda halaman

      const contentToSave = {
          title: this.content.title || '',
          description: this.content.description || '',
      };
      formData.append('content', JSON.stringify(contentToSave));
      
      // (Jika nanti ada upload gambar, tambahkan di sini logic-nya)
      
      try { 
        const response = await axios.post(API_URL, formData, { 
          headers: { 'Content-Type': 'multipart/form-data' } 
        }); 
        
        alert(response.data.message || 'Berhasil disimpan!'); 
        await this.fetchContent(); 
      } catch (error) { 
        console.error("Gagal:", error.response?.data || error.message); 
        alert("Gagal menyimpan!"); 
      } finally {
        this.isLoading = false;
      }
    }, 
  },
};
</script>

<style scoped>
.form-input-underline { display: block; width: 100%; font-size: 1.125rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-input-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
.form-textarea-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-textarea-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
</style>