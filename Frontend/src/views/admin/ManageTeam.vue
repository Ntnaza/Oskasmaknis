<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
      <!-- Form Card -->
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-blueGray-700 text-xl font-bold">
            {{ isEditing ? 'Edit Anggota Tim' : 'Tambah Anggota Tim Baru' }}
          </h6>
        </div>
        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
          <form @submit.prevent="submitForm">
            <div class="flex flex-wrap mt-6">
              <!-- Input Nama -->
              <div class="w-full lg:w-6/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Lengkap</label>
                  <input type="text" v-model="form.name" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" required/>
                </div>
              </div>
              <!-- Input Jabatan -->
              <div class="w-full lg:w-3/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Jabatan</label>
                  <input type="text" v-model="form.position" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" required/>
                </div>
              </div>
              <!-- Input Urutan -->
              <div class="w-full lg:w-3/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Urutan</label>
                  <input type="number" v-model.number="form.order" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" required/>
                </div>
              </div>

              <!-- Upload Foto -->
              <div class="w-full px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Foto</label>
                  <input type="file" @change="handleFileChange" ref="photoInput" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                </div>
                <div v-if="form.photo_path">
                  <p class="text-sm text-blueGray-600 mb-2">Foto Saat Ini:</p>
                  <img :src="getFullImageUrl(form.photo_path)" class="w-24 h-24 rounded-full shadow-lg object-cover"/>
                </div>
              </div>
            </div>

            <hr class="mt-6 border-b-1 border-blueGray-300" />

            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Link Media Sosial</h6>
            
            <!-- Daftar Link Dinamis -->
            <div v-for="(link, index) in form.social_links" :key="index" class="flex flex-wrap items-center mb-4 border-b pb-4">
                <div class="w-full lg:w-5/12 px-4">
                    <label class="block text-blueGray-600 text-xs font-bold mb-2">Platform {{ index + 1 }} (e.g., twitter, instagram)</label>
                    <input type="text" v-model="link.platform" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow w-full"/>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                    <label class="block text-blueGray-600 text-xs font-bold mb-2">URL {{ index + 1 }}</label>
                    <input type="text" v-model="link.url" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow w-full"/>
                </div>
                <div class="w-full lg:w-1/12 px-4 mt-4">
                    <button @click.prevent="removeSocialLink(index)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="w-full px-4 mt-4">
                <button @click.prevent="addSocialLink" class="bg-blueGray-700 text-white font-bold text-xs px-4 py-2 rounded shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Link
                </button>
            </div>


            <hr class="mt-6 border-b-1 border-blueGray-300" />
            
            <!-- Tombol Aksi -->
            <div class="w-full px-4 mt-8">
              <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Simpan Perubahan' : 'Tambah Anggota' }}
              </button>
              <button v-if="isEditing" @click.prevent="resetForm" class="bg-blueGray-500 text-white active:bg-blueGray-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg ml-2" type="button">
                <i class="fas fa-times mr-2"></i> Batal Edit
              </button>
            </div>

          </form>
        </div>
      </div>

      <!-- Tabel Daftar Anggota -->
      <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-lg rounded mt-8">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <h3 class="font-semibold text-lg text-blueGray-700">Daftar Anggota Tim</h3>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Foto</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Nama</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Jabatan</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="member in teamMembers" :key="member.id" class="hover:bg-blueGray-50">
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <img :src="getFullImageUrl(member.photo_path)" class="h-12 w-12 bg-white rounded-full border object-cover" :alt="member.name" />
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">{{ member.name }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ member.position }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <button @click="editTeamMember(member)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button @click="deleteTeamMember(member.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
const API_URL = 'http://localhost:8000/api/team-members';

export default {
  data() {
    return {
      teamMembers: [],
      form: {
        id: null,
        name: '',
        position: '',
        photo_path: null,
        social_links: [],
        order: 0
      },
      selectedFile: null,
      isEditing: false,
    };
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/120x120/E2E8F0/A0AEC0?text=Foto';
      if (path.startsWith('http')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    handleFileChange(event) {
      this.selectedFile = event.target.files[0];
    },
    addSocialLink() {
        if (!Array.isArray(this.form.social_links)) {
            this.form.social_links = [];
        }
        this.form.social_links.push({ platform: '', url: '' });
    },
    removeSocialLink(index) {
        this.form.social_links.splice(index, 1);
    },
    async fetchTeamMembers() {
      try {
        const response = await axios.get(API_URL);
        this.teamMembers = response.data;
      } catch (error) {
        console.error("Gagal mengambil daftar anggota tim:", error);
      }
    },
    async submitForm() {
      const formData = new FormData();
      formData.append('name', this.form.name || '');
      formData.append('position', this.form.position || '');
      formData.append('order', this.form.order || 0);

      // --- PERBAIKAN UTAMA DI SINI ---
      // Daripada mengirim sebagai string JSON, kita pecah menjadi format array PHP
      const socialLinks = Array.isArray(this.form.social_links) ? this.form.social_links : [];
      socialLinks.forEach((link, index) => {
        formData.append(`social_links[${index}][platform]`, link.platform || '');
        formData.append(`social_links[${index}][url]`, link.url || '');
      });

      if (this.selectedFile) {
        formData.append('photo_file', this.selectedFile);
      }
      
      try {
        if (this.isEditing) {
          formData.append('_method', 'PUT');
          await axios.post(`${API_URL}/${this.form.id}`, formData);
          alert('Anggota tim berhasil diperbarui!');
        } else {
          await axios.post(API_URL, formData);
          alert('Anggota tim baru berhasil ditambahkan!');
        }
        this.resetForm();
        this.fetchTeamMembers();
      } catch (error) {
        console.error("Gagal menyimpan data anggota tim:", error.response.data);
        alert('Gagal menyimpan data.');
      }
    },
    editTeamMember(member) {
      this.isEditing = true;
      this.form = { 
          ...member, 
          social_links: Array.isArray(member.social_links) ? member.social_links : [] 
      };
      window.scrollTo(0, 0);
    },
    async deleteTeamMember(id) {
      if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
        try {
          await axios.delete(`${API_URL}/${id}`);
          alert('Anggota tim berhasil dihapus.');
          this.fetchTeamMembers();
        } catch (error) {
          console.error("Gagal menghapus anggota tim:", error);
          alert('Gagal menghapus anggota tim.');
        }
      }
    },
    resetForm() {
      this.isEditing = false;
      this.form = {
        id: null,
        name: '',
        position: '',
        photo_path: null,
        social_links: [],
        order: 0
      };
      this.selectedFile = null;
      if (this.$refs.photoInput) {
        this.$refs.photoInput.value = '';
      }
    }
  },
  mounted() {
    this.fetchTeamMembers();
  },
};
</script>

