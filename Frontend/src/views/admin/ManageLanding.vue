<template>
  <div class="flex-auto px-4 lg:px-10 py-10">
    <form @submit.prevent="saveContent">
      <div class="flex flex-wrap">

        <div class="w-full lg:w-12/12 px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
            <input type="text" v-model="content.title" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          </div>
        </div>
        <div class="w-full lg:w-12/12 px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Subtitel</label>
            <textarea v-model="content.subtitle" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" rows="4"></textarea>
          </div>
        </div>

        <div class="w-full lg:w-12/12 px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar Latar Baru</label>
            <input type="file" @change="handleFileChange" ref="fileInput" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
            <small class="mt-1 text-blueGray-500">Kosongkan jika tidak ingin mengubah gambar.</small>
          </div>
            <div v-if="content.hero_image_url" class="mt-4"> <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
            <img :src="getFullImageUrl(content.hero_image_url)" class="w-64 h-auto rounded shadow-lg"/>
          </div>
        </div>

        <div class="w-full lg:w-12/12 px-4 mt-6">
          <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow" type="submit">
            Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
const API_URL = 'http://localhost:8000/api/page-content/landing';

export default {
  data() {
    return {
      content: {
        title: "",
        subtitle: "",
        hero_image_url: "",
      },
      selectedFile: null,
    };
  },
  methods: {
    getFullImageUrl(path) {
        if (!path) return '';
        if (path.startsWith('http')) return path;
        // Gunakan Base URL API jika perlu
        const baseUrl = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
        return `${baseUrl}/storage/${path}`;
    },
    handleFileChange(event) {
        this.selectedFile = event.target.files[0];
    },
    async fetchContent() {
      try {
        const response = await axios.get(API_URL);
        this.content = response.data.data || response.data;
      } catch (error) {
        console.error("Gagal memuat data:", error);
        alert("Gagal memuat data yang ada.");
      }
    },
    async saveContent() {
      const formData = new FormData();
      formData.append('title', this.content.title || '');
      formData.append('subtitle', this.content.subtitle || '');

      if (this.selectedFile) {
        formData.append('hero_image_file', this.selectedFile);
      }

      formData.append('_method', 'PUT'); // Untuk Laravel

      try {
        const response = await axios.post(API_URL, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        alert(response.data.message || 'Berhasil disimpan!');
        await this.fetchContent(); // Muat ulang data setelah simpan
        this.selectedFile = null;
        if (this.$refs.fileInput) {
           this.$refs.fileInput.value = '';
        }
      } catch (error) {
        console.error("Gagal menyimpan data:", error.response ? error.response.data : error.message);
        alert("Gagal menyimpan data!");
      }
    },
  },
  mounted() {
    this.fetchContent();
  },
};
</script>