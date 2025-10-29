<template>
  <form @submit.prevent="saveContent">
    <div class="flex flex-wrap">

      <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6 w-full">
        Konten Hero
      </h6>

      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
          <input type="text" v-model="content.title" class="form-input-underline"/>
        </div>
      </div>
      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Subtitel</label>
          <textarea v-model="content.subtitle" class="form-textarea-underline" rows="4"></textarea>
        </div>
      </div>

      <div class="w-full lg:w-12/12">
        <div class="relative w-full mb-3">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar Latar Baru</label>
          <input type="file" @change="handleFileChange" ref="fileInput" class="border px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          <small class="mt-1 text-blueGray-500">Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>
        <div v-if="content.hero_image_url" class="mt-4">
          <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
          <img :src="getFullImageUrl(content.hero_image_url)" class="w-64 h-auto rounded shadow-lg"/>
        </div>
      </div>

      <div class="w-full lg:w-12/12 mt-8">
        <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
          Simpan Perubahan
        </button>
      </div>
    </div>
  </form>
</template>

<script>
// Script tidak berubah
import axios from "axios";
const API_URL = 'http://localhost:8000/api/page-content/landing';
export default {
  data() { return { content: { title: "", subtitle: "", hero_image_url: "" }, selectedFile: null, }; },
  methods: { getFullImageUrl(path) { if (!path) return ''; if (path.startsWith('http')) return path; const baseUrl = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000'; return `${baseUrl}/storage/${path}`; }, handleFileChange(event) { this.selectedFile = event.target.files[0]; }, async fetchContent() { try { const response = await axios.get(API_URL); this.content = response.data.data || response.data; } catch (error) { console.error("Gagal memuat data:", error); alert("Gagal memuat data."); } }, async saveContent() { const formData = new FormData(); formData.append('title', this.content.title || ''); formData.append('subtitle', this.content.subtitle || ''); if (this.selectedFile) { formData.append('hero_image_file', this.selectedFile); } formData.append('_method', 'PUT'); try { const response = await axios.post(API_URL, formData, { headers: { 'Content-Type': 'multipart/form-data' } }); alert(response.data.message || 'Berhasil disimpan!'); await this.fetchContent(); this.selectedFile = null; if (this.$refs.fileInput) { this.$refs.fileInput.value = ''; } } catch (error) { console.error("Gagal:", error.response?.data || error.message); alert("Gagal menyimpan!"); } }, },
  mounted() { this.fetchContent(); },
};
</script>

<style scoped>
/* Style tetap sama */
.form-input-underline { display: block; width: 100%; font-size: 1.125rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-input-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
.form-textarea-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-textarea-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
</style>