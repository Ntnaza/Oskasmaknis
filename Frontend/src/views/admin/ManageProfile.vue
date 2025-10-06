<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-blueGray-700 text-xl font-bold">Kelola Halaman Profil Organisasi</h6>
        </div>
        <div class="flex-auto px-4 lg:px-10 py-10">
          <form @submit.prevent="saveProfile">
            
            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Utama</h6>
            <div class="flex flex-wrap">
              <!-- Input Nama Organisasi -->
              <div class="w-full lg:w-6/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Organisasi</label>
                  <input type="text" v-model="form.name" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                </div>
              </div>
              <!-- Input Lokasi -->
              <div class="w-full lg:w-6/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Lokasi</label>
                  <input type="text" v-model="form.location" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                </div>
              </div>
              <!-- Input About / Deskripsi -->
              <div class="w-full px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Tentang Organisasi (Visi, Misi, dll.)</label>
                  <textarea v-model="form.about" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" rows="6"></textarea>
                </div>
              </div>
            </div>

            <hr class="mt-6 border-b-1 border-blueGray-300" />

            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Gambar</h6>
            <div class="flex flex-wrap">
                <!-- Upload Gambar Profil (Logo) -->
                <div class="w-full lg:w-6/12 px-4">
                    <div class="relative w-full mb-3">
                        <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Foto Profil / Logo</label>
                        <input type="file" @change="handleFileChange($event, 'profile')" ref="profileImageInput" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                    </div>
                    <div v-if="form.profile_image_path">
                        <p class="text-sm text-blueGray-600 mb-2">Foto Profil Saat Ini:</p>
                        <img :src="getFullImageUrl(form.profile_image_path)" class="w-32 h-32 rounded-full shadow-lg object-cover"/>
                    </div>
                </div>

                <!-- Upload Gambar Latar (Header) -->
                <div class="w-full lg:w-6/12 px-4">
                    <div class="relative w-full mb-3">
                        <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar Latar Halaman</label>
                        <input type="file" @change="handleFileChange($event, 'header')" ref="headerImageInput" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                    </div>
                    <div v-if="form.header_image_path">
                        <p class="text-sm text-blueGray-600 mb-2">Gambar Latar Saat Ini:</p>
                        <img :src="getFullImageUrl(form.header_image_path)" class="w-64 h-auto rounded shadow-lg"/>
                    </div>
                </div>
            </div>

            <hr class="mt-6 border-b-1 border-blueGray-300" />
            
            <!-- Tombol Simpan -->
            <div class="w-full px-4 mt-6">
              <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow" type="submit">
                Simpan Perubahan Profil
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const API_URL = 'http://localhost:8000/api/profile';

export default {
  data() {
    return {
      form: {
        name: "",
        location: "",
        about: "",
        profile_image_path: null,
        header_image_path: null,
      },
      selectedProfileImage: null,
      selectedHeaderImage: null,
    };
  },
  methods: {
    getFullImageUrl(path) {
        if (!path) return '';
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
    },
    handleFileChange(event, type) {
        if (type === 'profile') {
            this.selectedProfileImage = event.target.files[0];
        } else if (type === 'header') {
            this.selectedHeaderImage = event.target.files[0];
        }
    },
    async fetchProfile() {
      try {
        const response = await axios.get(API_URL);
        this.form = response.data;
      } catch (error) {
        console.error("Gagal memuat data profil:", error);
      }
    },
    async saveProfile() {
      const formData = new FormData();
      
      formData.append('name', this.form.name || '');
      formData.append('location', this.form.location || '');
      formData.append('about', this.form.about || '');
      
      if (this.selectedProfileImage) {
        formData.append('profile_image', this.selectedProfileImage);
      }
      if (this.selectedHeaderImage) {
        formData.append('header_image', this.selectedHeaderImage);
      }
      formData.append('_method', 'POST'); 
      
      try {
        const response = await axios.post(API_URL, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        alert(response.data.message);
        this.fetchProfile();
        
        // Reset file inputs
        this.selectedProfileImage = null;
        this.selectedHeaderImage = null;
        this.$refs.profileImageInput.value = '';
        this.$refs.headerImageInput.value = '';
      } catch (error) {
        console.error("Gagal menyimpan profil:", error.response.data);
        alert("Gagal menyimpan profil!");
      }
    },
  },
  mounted() {
    this.fetchProfile();
  },
};
</script>
