<template>
  <div>
    <form @submit.prevent="saveContent">
      <div class="w-full mb-6 mt-6">
        <h6 class="text-blueGray-700 text-xl font-bold">Informasi Pembina</h6>
        <!-- Indikator Angkatan -->
        <small v-if="angkatanStore.activeAngkatan" class="text-emerald-600 font-bold block mt-1">
            Mengelola untuk: {{ angkatanStore.activeAngkatan.name }}
        </small>
      </div>

      <div class="flex flex-wrap">
        <div class="w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul Seksi</label>
          <input type="text" v-model="content.title" class="form-input-underline" placeholder="Contoh: Dibimbing Oleh"/>
        </div>
        <div class="w-full lg:w-6/12">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Pembina</label>
            <input type="text" v-model="content.pembina_name" class="form-input-underline" placeholder="Nama Lengkap"/>
          </div>
        </div>
        <div class="w-full lg:w-6/12 lg:pl-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Jabatan Pembina</label>
            <input type="text" v-model="content.pembina_title" class="form-input-underline" placeholder="Contoh: Kepala Sekolah"/>
          </div>
        </div>
        <div class="w-full mt-8">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Pembina</label>
            <input type="file" @change="handlePembinaFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full shadow-sm"/>
            <div v-if="pembinaPreviewUrl || content.pembina_image_path" class="mt-4">
                <img :src="pembinaPreviewUrl || getFullImageUrl(content.pembina_image_path)" class="rounded-lg max-w-200-px shadow-lg"/>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-8 border-b-1 border-blueGray-200" />
      
      <div class="w-full flex justify-end">
        <button
          class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
          type="submit"
          :disabled="isLoading"
        >
          <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { useAngkatanStore } from '@/stores/angkatan'; 
import Swal from 'sweetalert2';

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
const SECTION_KEY = 'index-pembina';

// Endpoint Fetch & Update
const FETCH_URL = `${API_BASE_URL}/api/content-block/${SECTION_KEY}`;
const UPDATE_URL = `${API_BASE_URL}/api/content-blocks/update-bulk`;

export default {
  name: "ManageIndexPembina",
  data() {
    return {
      angkatanStore: useAngkatanStore(),
      content: {
        title: '',
        pembina_name: '',
        pembina_title: '',
        pembina_image_path: null
      },
      pembinaFile: null,
      pembinaPreviewUrl: null,
      isLoading: false,
    };
  },
  watch: {
    'angkatanStore.selectedId': {
      handler(newVal) {
        if (newVal) this.fetchData();
      },
      immediate: true
    }
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return null;
      if (path.startsWith('http')) return path;
      return `${API_BASE_URL}/storage/${path}`;
    },
    handlePembinaFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.pembinaFile = file;
        this.pembinaPreviewUrl = URL.createObjectURL(file);
      }
    },
    
    async fetchData() {
      if (!this.angkatanStore.selectedId) return;
      this.isLoading = true;
      try {
        const response = await axios.get(FETCH_URL, {
            params: { angkatan_id: this.angkatanStore.selectedId }
        });
        const blockData = response.data.data;
        
        if (blockData && blockData.content) {
          this.content = {
            title: blockData.content.title || '',
            pembina_name: blockData.content.pembina_name || '',
            pembina_title: blockData.content.pembina_title || '',
            pembina_image_path: blockData.content.pembina_image_path || null
          };
        } else {
          this.resetForm();
        }
      } catch (error) {
         console.error("Gagal memuat data:", error);
         this.resetForm();
      } finally {
         this.isLoading = false;
      }
    },

    resetForm() {
        this.content = { title: '', pembina_name: '', pembina_title: '', pembina_image_path: null };
        this.pembinaFile = null;
        this.pembinaPreviewUrl = null;
    },

    async saveContent() {
      if (!this.angkatanStore.selectedId) {
          Swal.fire('Error', 'Pilih angkatan terlebih dahulu!', 'error');
          return;
      }

      this.isLoading = true;
      const formData = new FormData();
      
      // 1. Kirim Angkatan ID
      formData.append('angkatan_id', this.angkatanStore.selectedId);

      // 2. Susun Data Block
      const blocksData = [
          {
              section_key: SECTION_KEY,
              page_slug: 'index',
              content: this.content
          }
      ];
      formData.append('blocks', JSON.stringify(blocksData));

      // 3. Kirim File (Key 'pembinaFile' harus sesuai dengan Controller updateBulk)
      if (this.pembinaFile) {
          formData.append('pembinaFile', this.pembinaFile);
      }

      try {
        await axios.post(UPDATE_URL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data pembina berhasil disimpan.',
          timer: 1500,
          showConfirmButton: false,
        });
        
        this.pembinaFile = null;
        this.fetchData();

      } catch (error) {
        console.error("Gagal simpan:", error);
        Swal.fire('Error', 'Gagal menyimpan data.', 'error');
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
.form-input-underline {
  display: block; width: 100%; font-size: 1.125rem;
  padding: 0.5rem 0.125rem;
  border: 0; border-bottom: 2px solid #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-input-underline:focus {
  outline: none; border-color: #10B981;
}
</style>