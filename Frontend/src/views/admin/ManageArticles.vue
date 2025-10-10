<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
      <div v-if="notification.show" 
           :class="notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
           class="text-white text-sm font-bold px-4 py-3 rounded-lg shadow-lg fixed top-5 right-5 z-50 transition-all duration-300"
           role="alert">
        <p>{{ notification.message }}</p>
      </div>

      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-2xl rounded-2xl bg-white border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-slate-700 text-xl font-bold">
            {{ isEditing ? 'Edit Artikel/Galeri' : 'Buat Artikel/Galeri Baru' }}
          </h6>
        </div>
        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
          <form @submit.prevent="submitForm">
            <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Dasar</h6>
            <div class="flex flex-wrap">
              <div class="w-full px-4 mb-4">
                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Judul</label>
                <input type="text" v-model="form.title" class="form-input-line" required/>
              </div>
              <div class="w-full px-4 mb-4">
                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Ringkasan (Excerpt)</label>
                <textarea v-model="form.excerpt" class="form-input-line" rows="2" placeholder="Tulis ringkasan singkat..."></textarea>
              </div>
              <div class="w-full px-4 mb-4">
                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Isi Konten</label>
                <textarea v-model="form.content" class="form-input-line" rows="5" placeholder="Tulis isi artikel..."></textarea>
              </div>
            </div>

            <hr class="mt-6 border-b-1 border-slate-200" />
            <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">Gambar</h6>
            <div class="flex flex-wrap">
              <div class="w-full lg:w-6/12 px-4 mb-4">
                  <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Gambar Utama (Featured)</label>
                  <input type="file" @change="handleFileChange($event, 'featured')" ref="featuredInput" class="custom-file-input"/>
                  <div v-if="form.featured_image_path" class="mt-4">
                    <p class="text-xs text-slate-500 mb-2">Gambar saat ini:</p>
                    <img :src="getFullImageUrl(form.featured_image_path)" class="w-48 h-auto rounded-lg shadow-lg object-cover"/>
                  </div>
              </div>
              <div class="w-full lg:w-6/12 px-4 mb-4">
                  <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Gambar Galeri (Bisa Pilih Banyak)</label>
                  <input type="file" @change="handleFileChange($event, 'gallery')" ref="galleryInput" class="custom-file-input" multiple/>
                  <div v-if="form.gallery && form.gallery.length" class="mt-4">
                     <p class="text-xs text-slate-500 mb-2">Gambar galeri saat ini:</p>
                     <div class="flex flex-wrap gap-2">
                       <div v-for="(path, index) in form.gallery" :key="index" class="relative">
                         <img :src="getFullImageUrl(path)" class="w-20 h-20 rounded-lg shadow-md object-cover" :style="{ opacity: imagesToDelete.includes(path) ? '0.4' : '1' }"/>
                         <button 
                           v-if="isEditing"
                           @click.prevent="markImageForDeletion(path)"
                           class="absolute top-0 right-0 -m-3 bg-red-500 text-white rounded-full h-8 w-8 flex items-center justify-center text-lg font-bold shadow-lg hover:bg-red-600 focus:outline-none"
                         >
                           &times;
                         </button>
                       </div>
                     </div>
                  </div>
              </div>
            </div>
            
            <hr class="mt-8 border-b-1 border-slate-200" />
            <div class="w-full px-4 mt-8 flex items-center">
              <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Simpan Perubahan' : 'Publikasikan' }}
              </button>
              <button v-if="isEditing" @click.prevent="resetForm" class="bg-slate-200 text-slate-700 active:bg-slate-300 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg ml-2" type="button">
                <i class="fas fa-times mr-2"></i> Batal Edit
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-2xl rounded-2xl mt-8">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <h3 class="font-semibold text-lg text-slate-700">Daftar Artikel & Galeri</h3>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Gambar</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Judul</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Ringkasan</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-slate-50 text-slate-500 border-slate-100">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="article in articles" :key="article.id" class="hover:bg-slate-50">
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <img :src="getFullImageUrl(article.featured_image_path)" class="h-12 w-12 bg-white rounded-md border object-cover" :alt="article.title" />
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">{{ article.title }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs p-4" style="max-width: 300px; white-space: normal;">{{ article.excerpt }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <button @click="editArticle(article)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md hover:bg-emerald-600">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button @click="deleteArticle(article.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md hover:bg-red-600">
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

// URL untuk mengambil SEMUA artikel (tanpa paginasi) untuk admin
const FETCH_URL = 'http://localhost:8000/api/all-articles'; 
// URL dasar untuk CREATE, UPDATE, DELETE yang menggunakan Route::apiResource
const RESOURCE_URL = 'http://localhost:8000/api/articles';

export default {
  data() {
    return {
      articles: [],
      form: this.getInitialForm(),
      selectedFeaturedFile: null,
      selectedGalleryFiles: [],
      isEditing: false,
      notification: { show: false, message: '', type: 'success' },
      imagesToDelete: [],
    };
  },
  methods: {
    getInitialForm() {
      return {
        id: null,
        title: '',
        excerpt: '',
        content: '',
        featured_image_path: null,
        gallery: [],
      };
    },
    showNotification(message, type = 'success') {
      this.notification = { show: true, message, type };
      setTimeout(() => { this.notification.show = false; }, 3000);
    },
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/300x200/E2E8F0/A0AEC0?text=Gambar';
      if (path.startsWith('http')) return path;
      if (path.startsWith('blob:')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    handleFileChange(event, type) {
      if (type === 'featured') {
        this.selectedFeaturedFile = event.target.files[0];
        if (this.selectedFeaturedFile) {
          this.form.featured_image_path = URL.createObjectURL(this.selectedFeaturedFile);
        }
      } else if (type === 'gallery') {
        this.selectedGalleryFiles = Array.from(event.target.files);
      }
    },
    markImageForDeletion(path) {
      if (this.imagesToDelete.includes(path)) {
        this.imagesToDelete = this.imagesToDelete.filter(p => p !== path);
      } else {
        this.imagesToDelete.push(path);
      }
    },
    async fetchArticles() {
      try {
        const response = await axios.get(FETCH_URL);
        this.articles = response.data;
      } catch (error) {
        this.showNotification('Gagal memuat artikel.', 'error');
      }
    },
    async submitForm() {
      const formData = new FormData();
      
      Object.keys(this.form).forEach(key => {
        if (key !== 'id' && key !== 'gallery' && this.form[key] !== null) {
          formData.append(key, this.form[key]);
        }
      });

      if (this.selectedFeaturedFile) {
        formData.append('featured_image_file', this.selectedFeaturedFile);
      }
      
      if (this.selectedGalleryFiles.length) {
        this.selectedGalleryFiles.forEach(file => {
          formData.append('gallery_files[]', file);
        });
      }

      try {
        if (this.isEditing) {
          formData.append('_method', 'PUT');
          if (this.imagesToDelete.length > 0) {
            formData.append('images_to_delete', JSON.stringify(this.imagesToDelete));
          }
          await axios.post(`${RESOURCE_URL}/${this.form.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          this.showNotification('Konten berhasil diperbarui!');
        } else {
          await axios.post(RESOURCE_URL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          this.showNotification('Konten baru berhasil dibuat!');
        }
        this.resetForm();
        await this.fetchArticles();
      } catch (error) {
        console.error("Gagal menyimpan data:", error.response ? error.response.data : error);
        this.showNotification('Gagal menyimpan data. Cek konsol.', 'error');
      }
    },
    editArticle(article) {
      this.isEditing = true;
      this.imagesToDelete = [];
      const articleCopy = JSON.parse(JSON.stringify(article));
      if (!articleCopy.gallery || !Array.isArray(articleCopy.gallery)) {
        articleCopy.gallery = [];
      }
      this.form = articleCopy;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    async deleteArticle(id) {
      if (confirm('Apakah Anda yakin ingin menghapus konten ini?')) {
        try {
          await axios.delete(`${RESOURCE_URL}/${id}`);
          this.showNotification('Konten berhasil dihapus.');
          if (this.isEditing && this.form.id === id) {
            this.resetForm();
          }
          await this.fetchArticles();
        } catch (error) {
          this.showNotification('Gagal menghapus konten.', 'error');
        }
      }
    },
    resetForm() {
      this.isEditing = false;
      this.form = this.getInitialForm();
      this.selectedFeaturedFile = null;
      this.selectedGalleryFiles = [];
      this.imagesToDelete = [];
      if (this.$refs.featuredInput) this.$refs.featuredInput.value = '';
      if (this.$refs.galleryInput) this.$refs.galleryInput.value = '';
    }
  },
  mounted() {
    this.fetchArticles();
  },
};
</script>

<style scoped>
.form-input-line {
  border: 0;
  border-bottom: 2px solid #e2e8f0;
  padding: 0.75rem 0.25rem;
  background-color: transparent;
  width: 100%;
  transition: border-color 0.3s ease;
}
.form-input-line:focus {
  outline: none;
  box-shadow: none;
  border-bottom-color: #10b981;
}
.custom-file-input {
  border: 1px solid #e2e8f0;
  padding: 0.5rem;
  border-radius: 0.5rem;
  width: 100%;
  cursor: pointer;
}
</style>