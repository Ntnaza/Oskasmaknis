<template>
  <div>
    <form @submit.prevent="saveContent">
      <div class="flex flex-wrap">
        <div class="w-full mb-6 mt-6">
            <h6 class="text-blueGray-700 text-xl font-bold">
              Informasi Sambutan Ketua
            </h6>
            <small v-if="angkatanStore.activeAngkatan" class="text-emerald-600 font-bold block mt-1">
                Mengelola untuk: {{ angkatanStore.activeAngkatan.name }}
            </small>
        </div>

        <div class="w-full lg:w-6/12">
          <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Ketua OSIS</h6>
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua OSIS</label>
            <input type="text" v-model="content.ketua_osis_name" class="form-input-underline" placeholder="Nama Lengkap"/>
          </div>
          
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua OSIS</label>
            <input type="file" @change="handleOsisFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full shadow-sm"/>
            <div v-if="osisPreviewUrl || content.ketua_osis_image_path" class="mt-4">
                <img :src="osisPreviewUrl || getFullImageUrl(content.ketua_osis_image_path)" class="rounded-lg h-32 object-cover shadow-lg"/>
            </div>
          </div>

          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua OSIS</label>
            <textarea rows="6" v-model="content.ketua_osis_sambutan" class="form-textarea-underline" placeholder="Tulis kata sambutan..."></textarea>
          </div>

          <!-- PILIH PROGRAM KERJA UNGGULAN -->
          <div class="relative w-full mb-8 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Program Kerja Unggulan (Max 3)</label>
            
            <div v-for="(programId, index) in content.featured_work_program_ids" :key="index" class="flex items-center mb-3">
              <select v-model="content.featured_work_program_ids[index]" class="border-0 px-3 py-2 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150 flex-1">
                  <option :value="null" disabled>-- Pilih Program --</option>
                  <option v-for="p in workPrograms" :key="p.id" :value="p.id">{{ p.title }}</option>
              </select>
              <button @click.prevent="removeFeaturedProgram(index)" type="button" class="ml-2 bg-red-500 text-white font-bold uppercase text-xs px-3 py-2 rounded shadow hover:bg-red-600">
                  <i class="fas fa-trash"></i>
              </button>
            </div>

            <button @click.prevent="addFeaturedProgram" type="button" class="mt-2 bg-blueGray-700 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:bg-blueGray-800">
                + Tambah Program
            </button>
          </div>
        </div>
        
        <div class="w-full lg:w-6/12 lg:pl-8">
           <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Ketua MPK</h6>
           <div class="relative w-full mb-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua MPK</label>
             <input type="text" v-model="content.ketua_mpk_name" class="form-input-underline" placeholder="Nama Lengkap"/>
           </div>
           
           <div class="relative w-full mb-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua MPK</label>
             <input type="file" @change="handleMpkFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full shadow-sm"/>
             <div v-if="mpkPreviewUrl || content.ketua_mpk_image_path" class="mt-4">
                <img :src="mpkPreviewUrl || getFullImageUrl(content.ketua_mpk_image_path)" class="rounded-lg h-32 object-cover shadow-lg"/>
            </div>
           </div>

           <div class="relative w-full mb-3">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua MPK</label>
             <textarea rows="6" v-model="content.ketua_mpk_sambutan" class="form-textarea-underline" placeholder="Tulis kata sambutan..."></textarea>
           </div>

           <div class="relative w-full mb-3 mt-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Visi MPK</label>
             <textarea rows="3" v-model="content.ketua_mpk_visi" class="form-textarea-underline" placeholder="Visi..."></textarea>
           </div>
           <div class="relative w-full mb-3">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Misi MPK</label>
             <textarea rows="3" v-model="content.ketua_mpk_misi" class="form-textarea-underline" placeholder="Misi..."></textarea>
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
const SECTION_KEY = 'index-sambutan-ketua';

// Endpoint khusus untuk fetch data
const FETCH_URL = `${API_BASE_URL}/api/content-block/${SECTION_KEY}`;
// Endpoint khusus untuk update bulk (karena logic upload file osis/mpk ada di sana)
const UPDATE_URL = `${API_BASE_URL}/api/content-blocks/update-bulk`;
const WORK_PROGRAMS_URL = `${API_BASE_URL}/api/work-programs-selection`;

export default {
  name: "ManageIndexSambutan",
  data() {
    return {
      angkatanStore: useAngkatanStore(),
      content: {
        ketua_osis_name: '', ketua_osis_sambutan: '', 
        ketua_mpk_name: '', ketua_mpk_sambutan: '', 
        ketua_osis_image_path: null, ketua_mpk_image_path: null,
        featured_work_program_ids: [], 
        ketua_mpk_visi: '', ketua_mpk_misi: '',
      },
      workPrograms: [], // Dropdown data
      osisFile: null,
      mpkFile: null,
      osisPreviewUrl: null,
      mpkPreviewUrl: null,
      isLoading: false,
    };
  },
  watch: {
    'angkatanStore.selectedId': {
      handler(newVal) {
        if (newVal) {
            this.fetchData();
            this.fetchWorkPrograms();
        }
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
    handleOsisFileChange(event) {
      const file = event.target.files[0];
      if (file) { this.osisFile = file; this.osisPreviewUrl = URL.createObjectURL(file); }
    },
    handleMpkFileChange(event) {
      const file = event.target.files[0];
      if (file) { this.mpkFile = file; this.mpkPreviewUrl = URL.createObjectURL(file); }
    },
    addFeaturedProgram() {
      if (!Array.isArray(this.content.featured_work_program_ids)) {
        this.content.featured_work_program_ids = [];
      }
      this.content.featured_work_program_ids.push(null);
    },
    removeFeaturedProgram(index) {
      this.content.featured_work_program_ids.splice(index, 1);
    },

    async fetchWorkPrograms() {
       if (!this.angkatanStore.selectedId) return;
       try {
           // Ambil proker KHUSUS angkatan ini
           const res = await axios.get(WORK_PROGRAMS_URL, {
               params: { angkatan_id: this.angkatanStore.selectedId }
           });
           this.workPrograms = res.data.data || res.data;
       } catch (e) {
           console.error("Gagal load proker:", e);
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
                ...this.content, // Keep defaults
                ...blockData.content,
                // Pastikan array selalu terinisialisasi
                featured_work_program_ids: Array.isArray(blockData.content.featured_work_program_ids) 
                    ? blockData.content.featured_work_program_ids 
                    : []
            };
        } else {
            // Reset form jika data kosong
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
        this.content = {
            ketua_osis_name: '', ketua_osis_sambutan: '', 
            ketua_mpk_name: '', ketua_mpk_sambutan: '', 
            ketua_osis_image_path: null, ketua_mpk_image_path: null,
            featured_work_program_ids: [], 
            ketua_mpk_visi: '', ketua_mpk_misi: '',
        };
        this.osisFile = null; this.mpkFile = null;
        this.osisPreviewUrl = null; this.mpkPreviewUrl = null;
    },

    async saveContent() {
      if (!this.angkatanStore.selectedId) {
          Swal.fire('Error', 'Pilih angkatan terlebih dahulu!', 'error');
          return;
      }

      this.isLoading = true;
      const formData = new FormData();
      
      // 1. Kirim Angkatan ID (PENTING)
      formData.append('angkatan_id', this.angkatanStore.selectedId);

      // 2. Susun data block dalam format array JSON (sesuai updateBulk)
      // Backend 'updateBulk' mengharapkan array of blocks
      const blocksData = [
          {
              section_key: SECTION_KEY,
              page_slug: 'index',
              content: this.content
          }
      ];
      formData.append('blocks', JSON.stringify(blocksData));

      // 3. Kirim File (Kunci file harus sesuai dengan Controller updateBulk)
      if (this.osisFile) formData.append('osisFile', this.osisFile);
      if (this.mpkFile) formData.append('mpkFile', this.mpkFile);

      try {
        await axios.post(UPDATE_URL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data sambutan berhasil disimpan.',
          timer: 1500,
          showConfirmButton: false,
        });
        
        // Refresh data
        this.osisFile = null; this.mpkFile = null;
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
.form-input-underline:focus { outline: none; border-color: #10B981; }

.form-textarea-underline {
  display: block; width: 100%; font-size: 1rem;
  padding: 0.5rem 0.125rem;
  border: 0; border-bottom: 2px solid #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-textarea-underline:focus { outline: none; border-color: #10B981; }
</style>