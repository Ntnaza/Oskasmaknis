<template>
  <div>
    <div v-if="notification.show"
         :class="notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
         class="text-white text-sm font-bold px-4 py-3 rounded-lg shadow-lg fixed top-5 right-5 z-50 transition-all duration-300"
         role="alert">
      <p>{{ notification.message }}</p>
    </div>

    <h6 class="text-slate-700 text-xl font-bold mb-6 mt-6"> {{ isEditing ? 'Edit Anggota Tim' : 'Tambah Anggota Tim Baru' }}</h6>

    <form @submit.prevent="submitForm">
      <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Utama</h6>
      <div class="flex flex-wrap mt-6">
        <div class="w-full lg:w-6/12">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Nama Lengkap</label>
            <input type="text" v-model="form.name" class="form-input-underline" required/>
          </div>
        </div>
        <div class="w-full lg:w-3/12 lg:pl-4">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Jabatan</label>
            <input type="text" v-model="form.position" class="form-input-underline" required/>
          </div>
        </div>
        <div class="w-full lg:w-3/12 lg:pl-4">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Urutan</label>
            <input type="number" v-model.number="form.order" class="form-input-underline" required/>
          </div>
        </div>
      </div>

      <hr class="mt-6 border-b-1 border-slate-200" />
      <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Gambar</h6>
      <div class="flex flex-wrap">
        <div class="w-full lg:w-6/12">
              <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Upload Foto Profil</label>
              <input type="file" @change="handleFileChange($event, 'photo')" ref="photoInput" class="custom-file-input" accept="image/*"/>
              <div v-if="photoPreviewUrl || form.photo_path" class="mt-4">
                <img :src="photoPreviewUrl || getFullImageUrl(form.photo_path)" class="w-24 h-24 rounded-full shadow-lg object-cover"/>
              </div>
        </div>
        <div class="w-full lg:w-6/12 lg:pl-4">
              <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Upload Gambar Latar</label>
              <input type="file" @change="handleFileChange($event, 'header')" ref="headerInput" class="custom-file-input" accept="image/*"/>
              <div v-if="headerPreviewUrl || form.header_photo_path" class="mt-4">
                <img :src="headerPreviewUrl || getFullImageUrl(form.header_photo_path, 'header')" class="w-48 h-auto rounded-lg shadow-lg object-cover"/>
              </div>
        </div>
      </div>

      <hr class="mt-6 border-b-1 border-slate-200" />
      <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Biodata & Visi Misi</h6>
      <div class="flex flex-wrap">
        <div class="w-full lg:w-4/12">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Jurusan</label>
          <input type="text" v-model="form.bio_data.jurusan" class="form-input-underline mb-4"/> 
        </div>
        <div class="w-full lg:w-4/12 lg:pl-4">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Tempat, Tanggal Lahir</label>
          <input type="text" v-model="form.bio_data.ttl" class="form-input-underline mb-4"/> 
        </div>
         <div class="w-full lg:w-4/12 lg:pl-4">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Hobi</label>
          <input type="text" v-model="form.bio_data.hobi" class="form-input-underline mb-4"/> 
        </div>
        <div class="w-full">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Kata Sambutan</label>
          <textarea v-model="form.bio_data.sambutan" class="form-textarea-underline mb-4" rows="3"></textarea> 
        </div>
         <div class="w-full">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Visi & Misi</label>
          <textarea v-model="form.bio_data.visi_misi" class="form-textarea-underline mb-4" rows="3"></textarea> 
        </div>
         <div class="w-full">
          <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Prestasi & Pengalaman</label>
          <textarea v-model="prestasi_string" class="form-textarea-underline mb-1" rows="4" placeholder="Satu prestasi per baris..."></textarea> 
          <small class="text-slate-500">Pisahkan setiap item dengan menekan tombol Enter.</small>
        </div>
      </div>

      <hr class="mt-6 border-b-1 border-slate-200" />
      <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Link Media Sosial</h6>
       <div v-for="(link, index) in form.social_links" :key="index" class="w-full mb-6">
         <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Pilih Platform & Masukkan URL</label>
         <div class="flex items-center gap-2 flex-wrap mb-3">
             <button type="button" @click="link.platform = 'instagram'" :class="{'social-icon-active': link.platform === 'instagram'}" class="social-icon"> <i class="fab fa-instagram"></i> </button>
             <button type="button" @click="link.platform = 'facebook'" :class="{'social-icon-active': link.platform === 'facebook'}" class="social-icon"> <i class="fab fa-facebook-square"></i> </button>
             <button type="button" @click="link.platform = 'tiktok'" :class="{'social-icon-active': link.platform === 'tiktok'}" class="social-icon"> <i class="fab fa-tiktok"></i> </button>
             <button type="button" @click="link.platform = 'twitter'" :class="{'social-icon-active': link.platform === 'twitter'}" class="social-icon"> <i class="fab fa-twitter"></i> </button>
             <button type="button" @click="link.platform = 'youtube'" :class="{'social-icon-active': link.platform === 'youtube'}" class="social-icon"> <i class="fab fa-youtube"></i> </button>
             <button type="button" @click="link.platform = 'linkedin'" :class="{'social-icon-active': link.platform === 'linkedin'}" class="social-icon"> <i class="fab fa-linkedin"></i> </button>
             <button type="button" @click="link.platform = 'whatsapp'" :class="{'social-icon-active': link.platform === 'whatsapp'}" class="social-icon"> <i class="fab fa-whatsapp"></i> </button>
             <button type="button" @click="link.platform = 'telegram'" :class="{'social-icon-active': link.platform === 'telegram'}" class="social-icon"> <i class="fab fa-telegram-plane"></i> </button>
             <button type="button" @click="link.platform = 'email'" :class="{'social-icon-active': link.platform === 'email'}" class="social-icon"> <i class="fas fa-envelope"></i> </button>
         </div>
         <div class="flex items-center gap-2 w-full">
             <input type="text" v-model="link.url" :placeholder="getPlaceholderForPlatform(link.platform)" class="form-input-underline flex-grow"/>
             <button @click.prevent="removeSocialLink(index)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow hover:bg-red-600 transition-colors flex-shrink-0">
               <i class="fas fa-trash text-xs"></i>
             </button>
         </div>
       </div>
       <div class="w-full mt-2">
         <button type="button" @click.prevent="addSocialLink" class="w-full border-2 border-dashed border-slate-300 rounded-lg text-slate-500 hover:border-emerald-500 hover:text-emerald-500 transition-all duration-300 py-3 font-bold text-sm focus:outline-none">
           <i class="fas fa-plus mr-2"></i> Tambah Media Sosial Lainnya
         </button>
       </div>
      
      <hr class="mt-8 border-b-1 border-slate-200" />
      <div class="w-full mt-8 flex items-center">
        <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 disabled:opacity-75 disabled:cursor-not-allowed" type="submit" :disabled="isLoading">
          <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
          <i v-else class="fas fa-save mr-2"></i>
          {{ isLoading ? (isEditing ? 'Menyimpan...' : 'Menambahkan...') : (isEditing ? 'Simpan Perubahan' : 'Tambah Anggota') }}
        </button>
        <button v-if="isEditing" @click.prevent="resetForm" class="bg-slate-200 text-slate-700 active:bg-slate-300 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none ml-2 mb-1 ease-linear transition-all duration-150" type="button">
          <i class="fas fa-times mr-2"></i> Batal Edit
        </button>
      </div>
    </form>

    <hr class="my-8 border-b-1 border-blueGray-200" />

    <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-2xl rounded-2xl mt-8">
      <div class="rounded-t mb-0 px-4 py-3 border-0">
        <h3 class="font-semibold text-lg text-slate-700">Daftar Anggota Tim</h3>
      </div>
      <div class="block w-full overflow-x-auto">
        <table class="items-center w-full bg-transparent border-collapse">
          <thead>
            <tr>
              <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Foto</th>
              <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Nama</th>
              <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Jabatan</th>
              <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in teamMembers" :key="member.id" class="hover:bg-slate-50">
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                <img :src="getFullImageUrl(member.photo_path)" class="h-12 w-12 bg-white rounded-full border object-cover" :alt="member.name" />
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">{{ member.name }}</td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ member.position }}</td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                <button @click="editTeamMember(member)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md hover:bg-emerald-600 transition-colors">
                  <i class="fas fa-pencil-alt"></i>
                </button>
                <button @click="confirmDelete(member.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md hover:bg-red-600 transition-colors">
                  <i class="fas fa-trash"></i>
                </button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    </div>
</template>

<script>
import axios from 'axios';
const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
const API_URL = `${API_BASE_URL}/api/team-members`;
export default {
  components: {},
  data() {
    return {
      teamMembers: [],
      isEditing: false,
      isLoading: false,
      notification: { show: false, message: '', type: 'success' },
      selectedPhotoFile: null,
      selectedHeaderFile: null,
      photoPreviewUrl: null,
      headerPreviewUrl: null,
      form: this.getInitialForm(),
      
      // Data Modal QR sudah dihapus
    };
  },
  computed: {
    prestasi_string: {
      get() {
        return ((this.form.bio_data && this.form.bio_data.prestasi) || []).join('\n');
      },
      set(value) {
        if (!this.form.bio_data) {
          this.form.bio_data = {};
        }
        this.form.bio_data.prestasi = value.split('\n').filter(p => p.trim() !== '');
      }
    },

    // Computed qrCodeUrl sudah dihapus
  },
  methods: {
    getInitialForm() {
      return {
        id: null,
        name: '',
        position: '',
        photo_path: null,
        header_photo_path: null,
        social_links: [],
        bio_data: { jurusan: '', ttl: '', hobi: '', sambutan: '', visi_misi: '', prestasi: [] },
        order: 0,
      };
    },
    showNotification(message, type = 'success') {
      this.notification = { show: true, message, type };
      setTimeout(() => {
        this.notification.show = false;
      }, 3000);
    },
    getFullImageUrl(path, type = 'profile') {
      const placeholder = type === 'header' ? 'https://placehold.co/1920x500/E2E8F0/A0AEC0?text=Latar' : 'https://placehold.co/120x120/E2E8F0/A0AEC0?text=Foto';
      if (!path) return placeholder;
      if (path.startsWith('http') || path.startsWith('blob')) return path;
      return `${API_BASE_URL}/storage/${path}`;
    },
    handleFileChange(event, type) {
      const file = event.target.files[0];
      if (!file) return;
      const previewUrl = URL.createObjectURL(file);
      if (type === 'photo') {
        this.selectedPhotoFile = file;
        this.photoPreviewUrl = previewUrl;
      } else if (type === 'header') {
        this.selectedHeaderFile = file;
        this.headerPreviewUrl = previewUrl;
      }
    },
    async fetchTeamMembers() {
      try {
        const response = await axios.get(API_URL);
        this.teamMembers = response.data.sort((a, b) => a.order - b.order);
      } catch (error) {
        console.error("Gagal ambil anggota:", error);
        this.showNotification('Gagal muat data.', 'error');
      }
    },
    async submitForm() {
      this.isLoading = true;
      const formData = new FormData();
      formData.append('name', this.form.name || '');
      formData.append('position', this.form.position || '');
      const orderValue = Number.isInteger(this.form.order) && this.form.order >= 0 ? this.form.order : 0;
      formData.append('order', orderValue);
      formData.append('social_links', JSON.stringify(Array.isArray(this.form.social_links) ? this.form.social_links.filter(link => link.platform && link.url) : []));
      formData.append('bio_data', JSON.stringify(this.form.bio_data || {}));
      if (this.selectedPhotoFile) formData.append('photo_file', this.selectedPhotoFile);
      if (this.selectedHeaderFile) formData.append('header_photo_file', this.selectedHeaderFile);
      try {
        if (this.isEditing) {
          formData.append('_method', 'PUT');
          await axios.post(`${API_URL}/${this.form.id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
          this.showNotification('Berhasil diperbarui!');
        } else {
          await axios.post(API_URL, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
          this.showNotification('Berhasil ditambah!');
        }
        await this.fetchTeamMembers();
        this.resetForm();
      } catch (error) {
        console.error("Gagal simpan:", error.response?.data || error);
        let msg = 'Gagal simpan.';
        if (error.response?.data?.message) {
          msg = error.response.data.message;
          if(error.response.data.errors) {
            msg += "\nDetails:\n" + Object.entries(error.response.data.errors).map(([f, m]) => `${f}: ${m.join(', ')}`).join("\n");
          }
        }
        this.showNotification(msg, 'error');
      } finally {
        this.isLoading = false;
      }
    },
    editTeamMember(member) {
      this.resetForm();
      this.isEditing = true;
      const memberCopy = JSON.parse(JSON.stringify(member));
      const initialForm = this.getInitialForm();
      const bioData = memberCopy.bio_data && typeof memberCopy.bio_data === 'object' ? memberCopy.bio_data : initialForm.bio_data;
      let socialLinksArray = [];
      if (memberCopy.social_links && typeof memberCopy.social_links === 'object' && !Array.isArray(memberCopy.social_links)) {
        Object.keys(memberCopy.social_links).forEach(p => {
          if (memberCopy.social_links[p]) {
            socialLinksArray.push({ platform: p, url: memberCopy.social_links[p] });
          }
        });
      } else if (Array.isArray(memberCopy.social_links)) {
        socialLinksArray = memberCopy.social_links;
      }
      this.form = {
        ...initialForm,
        ...memberCopy,
        bio_data: {
          ...initialForm.bio_data,
          ...bioData,
          prestasi: Array.isArray(bioData.prestasi) ? bioData.prestasi : []
        },
        social_links: socialLinksArray,
      };
      this.selectedPhotoFile = null;
      this.selectedHeaderFile = null;
      this.photoPreviewUrl = null;
      this.headerPreviewUrl = null;
      if (this.$refs.photoInput) this.$refs.photoInput.value = '';
      if (this.$refs.headerInput) this.$refs.headerInput.value = '';
      this.$el.closest('.overflow-y-auto')?.scrollTo({ top: 0, behavior: 'smooth' });
    },
    confirmDelete(id) {
      if (window.confirm('Yakin hapus?')) {
        this.deleteTeamMember(id);
      }
    },
    async deleteTeamMember(id) {
      try {
        await axios.delete(`${API_URL}/${id}`);
        this.showNotification('Berhasil dihapus.');
        await this.fetchTeamMembers();
        if (this.isEditing && this.form.id === id) {
          this.resetForm();
        }
      } catch (error) {
        console.error("Gagal hapus:", error);
        this.showNotification('Gagal hapus.', 'error');
      }
    },
    resetForm() {
      this.isEditing = false;
      this.form = this.getInitialForm();
      this.selectedPhotoFile = null;
      this.selectedHeaderFile = null;
      this.photoPreviewUrl = null;
      this.headerPreviewUrl = null;
      if (this.$refs.photoInput) this.$refs.photoInput.value = '';
      if (this.$refs.headerInput) this.$refs.headerInput.value = '';
    },
    addSocialLink() {
      if (!Array.isArray(this.form.social_links)) {
        this.form.social_links = [];
      }
      this.form.social_links.push({ platform: 'instagram', url: '' });
    },
    removeSocialLink(index) {
      if (Array.isArray(this.form.social_links)) {
        this.form.social_links.splice(index, 1);
      }
    },
    getPlaceholderForPlatform(platform) {
      const placeholders = {
        instagram: 'https://instagram.com/username',
        facebook: 'https://facebook.com/username',
        email: 'email@contoh.com',
        whatsapp: 'https://wa.me/628123456789',
        tiktok: 'https://tiktok.com/@username',
        twitter: 'https://twitter.com/username',
        youtube: 'https://youtube.com/channel/UC...',
        linkedin: 'https://linkedin.com/in/username',
        telegram: 'https://t.me/username'
      };
      return placeholders[platform] || 'Masukkan URL...';
    },

    // Method Modal QR (openQrModal, closeQrModal) sudah dihapus
  },
  mounted() {
    this.fetchTeamMembers();
  },
};
</script>

<style scoped>
/* (Style Anda tidak berubah) */
.form-input-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.75rem; padding-bottom: 0.75rem; border-width: 0; border-bottom-width: 2px; border-color: #e2e8f0; background-color: transparent; transition: border-color 0.3s ease; }
.form-input-underline:focus { outline: none; box-shadow: none; border-bottom-color: #10b981; }
.form-textarea-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.75rem; padding-bottom: 0.75rem; border-width: 0; border-bottom-width: 2px; border-color: #e2e8f0; background-color: transparent; transition: border-color 0.3s ease; }
.form-textarea-underline:focus { outline: none; box-shadow: none; border-bottom-color: #10b981; }
.custom-file-input { border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem; width: 100%; cursor: pointer; }
.custom-file-input:hover { background-color: #f8fafc; }
.custom-file-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 1px #10b981; }
.social-icon { border: 2px solid #e2e8f0; color: #94a3b8; border-radius: 9999px; width: 2.25rem; height: 2.25rem; display: inline-flex; align-items: center; justify-content: center; font-size: 1.125rem; transition: all 0.3s ease; }
.social-icon:hover { color: #334155; border-color: #cbd5e1; }
.social-icon:focus { outline: none; border-color: #10b981; }
.social-icon-active { color: #10b981; border-color: #10b981; transform: scale(1.1); }
</style>