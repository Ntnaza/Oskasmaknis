<template>
  <div>
    <div v-if="form.content.title !== undefined">
       <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">
         Kelola Bagian Promo
       </h6>
      <form @submit.prevent="saveContent">

        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Konten Utama</h6>
        <div class="flex flex-wrap">
          <div class="w-full lg:w-6/12">
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
              <input type="text" v-model="form.content.title" class="form-input-underline"/>
            </div>
          </div>
          <div class="w-full lg:w-6/12 lg:pl-4">
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Ikon Utama</label>
              <input type="text" v-model="form.content.icon" class="form-input-underline"/>
            </div>
          </div>
          <div class="w-full">
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
              <textarea v-model="form.content.description" class="form-textarea-underline" rows="4"></textarea>
            </div>
          </div>
           <div class="w-full">
            <div class="relative w-full mb-3">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar</label>
              <input type="file" @change="handleFileChange" ref="fileInput" class="border px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
            </div>
            <div v-if="form.content.image_url" class="mt-4">
               <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
              <img :src="getFullImageUrl(form.content.image_url)" class="w-48 h-auto rounded shadow-lg"/>
            </div>
          </div>
        </div>

        <hr class="mt-6 border-b-1 border-blueGray-300" />
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Daftar Poin Keunggulan</h6>
        <div v-for="(item, index) in form.content.list_items" :key="index" class="flex flex-wrap items-center mb-4 border-b pb-4">
          <div class="w-full lg:w-5/12">
              <label class="block text-blueGray-600 text-xs font-bold mb-2">Ikon Poin {{ index + 1 }}</label>
              <input type="text" v-model="item.icon" class="form-input-underline"/>
          </div>
          <div class="w-full lg:w-6/12 lg:pl-4">
              <label class="block text-blueGray-600 text-xs font-bold mb-2">Teks Poin {{ index + 1 }}</label>
              <input type="text" v-model="item.text" class="form-input-underline"/>
          </div>
          <div class="w-full lg:w-1/12 mt-4 lg:mt-0 lg:pl-4">
             <button @click.prevent="removeListItem(index)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow">
                 <i class="fas fa-trash"></i>
             </button>
          </div>
        </div>

        <div class="w-full mt-4">
            <button @click.prevent="addListItem" class="bg-blueGray-700 text-white font-bold text-xs px-4 py-2 rounded shadow hover:bg-blueGray-800">
                <i class="fas fa-plus mr-2"></i> Tambah Poin
            </button>
        </div>
        <hr class="mt-6 border-b-1 border-blueGray-300" />
        <div class="w-full mt-6">
          <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
            Simpan Perubahan
          </button>
        </div>

      </form>
    </div>
    <div v-else class="text-center py-10 text-blueGray-500">
       Memuat data promo section...
    </div>
  </div> </template>

<script>
import axios from "axios";
import { useAngkatanStore } from "@/stores/angkatan"; // 1. Import Store

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
const SECTION_KEY = 'landing-promo'; // Sesuaikan key di database (misal: landing-promo atau promo-section)
const API_URL = `${API_BASE_URL}/api/content-block/${SECTION_KEY}`;

export default {
  data() {
    return {
      angkatanStore: useAngkatanStore(), // 2. Inisialisasi Store
      form: {
        content: {
          title: "",
          description: "",
          icon: "",
          image_url: "",
          list_items: [],
        }
      },
      selectedFile: null,
      isLoading: false
    };
  },

  // 3. Watcher Otomatis
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

    addListItem() {
      if (!Array.isArray(this.form.content.list_items)) {
        this.form.content.list_items = [];
      }
      this.form.content.list_items.push({ icon: 'fas fa-check', text: '' });
    },

    removeListItem(index) {
      this.form.content.list_items.splice(index, 1);
    },

    async fetchContent() {
      if (!this.angkatanStore.selectedId) return; // Guard clause

      this.isLoading = true;
      try {
        const response = await axios.get(API_URL, {
            params: { angkatan_id: this.angkatanStore.selectedId } // 4. Kirim Parameter
        });

        // Handle jika data masih kosong (return template kosong dari backend)
        const blockData = response.data.data;
        
        // Safety check untuk struktur data
        const safeContent = blockData.content || {};
        
        this.form = {
          ...blockData,
          content: {
            title: safeContent.title || "",
            description: safeContent.description || "",
            icon: safeContent.icon || "",
            image_url: safeContent.image_url || "",
            list_items: Array.isArray(safeContent.list_items) ? safeContent.list_items : []
          }
        };

      } catch (error) {
        console.error("Gagal memuat data:", error);
        // Reset form jika error 404
        this.form.content = { title: "", description: "", icon: "", image_url: "", list_items: [] };
      } finally {
        this.isLoading = false;
      }
    },

    async saveContent() {
      if (!this.angkatanStore.selectedId) {
          alert("Pilih angkatan dulu!");
          return;
      }

      this.isLoading = true;
      const formData = new FormData();
      
      // 5. Kirim angkatan_id
      formData.append('angkatan_id', this.angkatanStore.selectedId);

      // Siapkan konten JSON
      const contentToSave = {
        ...this.form.content,
        list_items: Array.isArray(this.form.content.list_items) ? this.form.content.list_items : []
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
        
        // Refresh data agar sinkron
        await this.fetchContent();
        
        // Reset file input
        this.selectedFile = null;
        if (this.$refs.fileInput) {
          this.$refs.fileInput.value = '';
        }

      } catch (error) {
        console.error("Gagal simpan:", error);
        alert(`Gagal simpan! ${error.response?.data?.message || ''}`);
      } finally {
        this.isLoading = false;
      }
    }
  },
  // Mounted dihapus karena sudah di-handle Watcher
};
</script>

<style scoped>
.form-input-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-input-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
.form-textarea-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-textarea-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
</style>