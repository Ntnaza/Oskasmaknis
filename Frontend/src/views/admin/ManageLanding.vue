<template>
  <form @submit.prevent="saveContent">
    <div class="flex flex-wrap">

      <div class="w-full mb-6 mt-6">
        <h6 class="text-blueGray-700 text-xl font-bold">
          Konten Hero (Landing Page)
        </h6>
        <small v-if="angkatanStore.activeAngkatan" class="text-emerald-600 font-bold block mt-1">
            Mengelola untuk: {{ angkatanStore.activeAngkatan.name }}
        </small>
      </div>

      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
          <input type="text" v-model="content.title" class="form-input-underline" placeholder="Masukkan judul utama website..."/>
        </div>
      </div>
      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Subtitel</label>
          <textarea v-model="content.subtitle" class="form-textarea-underline" rows="4" placeholder="Masukkan deskripsi singkat..."></textarea>
        </div>
      </div>

      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-3">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar Latar Baru</label>
          <input type="file" @change="handleFileChange" ref="fileInput" class="border px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          <small class="mt-1 text-blueGray-500">Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>
        
        <div v-if="content.image_url" class="mt-4">
          <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
          <img :src="getFullImageUrl(content.image_url)" class="w-64 h-auto rounded shadow-lg object-cover"/>
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
import { useAngkatanStore } from "@/stores/angkatan"; 

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
const SECTION_KEY = 'landing-hero';
const API_URL = `${API_BASE_URL}/api/content-block/${SECTION_KEY}`;

export default {
  data() { 
    return { 
      angkatanStore: useAngkatanStore(),
      content: { 
        title: "", 
        subtitle: "", 
        // PERBAIKAN: Ubah default key jadi image_url
        image_url: "" 
      }, 
      selectedFile: null,
      isLoading: false
    }; 
  },
  
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
    getFullImageUrl(path) { 
      if (!path) return ''; 
      if (path.startsWith('http')) return path; 
      return `${API_BASE_URL}/storage/${path}`; 
    }, 
    
    handleFileChange(event) { 
      this.selectedFile = event.target.files[0]; 
    }, 
    
    async fetchContent() { 
      if (!this.angkatanStore.selectedId) return;

      this.isLoading = true;
      try { 
        const response = await axios.get(API_URL, {
            params: {
                angkatan_id: this.angkatanStore.selectedId
            }
        });
        
        const blockData = response.data.data;

        // MAPPING DATA DARI BACKEND KE FRONTEND
        if (blockData && blockData.content) {
             this.content = {
                 title: blockData.content.title || "",
                 subtitle: blockData.content.subtitle || "",
                 // PERBAIKAN: Ambil 'image_url' dari backend
                 image_url: blockData.content.image_url || "" 
             };
        } else {
             this.content = { title: "", subtitle: "", image_url: "" };
        }

      } catch (error) { 
        console.error("Gagal memuat data:", error); 
        this.content = { title: "", subtitle: "", image_url: "" };
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
      
      formData.append('angkatan_id', this.angkatanStore.selectedId);

      const contentToSave = {
          title: this.content.title || '',
          subtitle: this.content.subtitle || '',
          // PERBAIKAN: Kirim kembali image_url yang lama agar tidak hilang
          image_url: this.content.image_url 
      };
      formData.append('content', JSON.stringify(contentToSave));
      
      if (this.selectedFile) { 
        formData.append('image_file', this.selectedFile); 
      } 
      
      try { 
        const response = await axios.post(API_URL, formData, { 
          headers: { 'Content-Type': 'multipart/form-data' } 
        }); 
        
        alert(response.data.message || 'Berhasil disimpan!'); 
        
        await this.fetchContent(); 
        
        this.selectedFile = null; 
        if (this.$refs.fileInput) { 
          this.$refs.fileInput.value = ''; 
        } 
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