<template>
  <div v-if="form.content.title !== undefined" class="flex-auto px-4 lg:px-10 py-10">
    <form @submit.prevent="saveContent">
      
      <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Konten Utama</h6>
      <div class="flex flex-wrap">
        <div class="w-full lg:w-6/12 px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
            <input type="text" v-model="form.content.title" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          </div>
        </div>
        <div class="w-full lg:w-6/12 px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Ikon Utama</label>
            <input type="text" v-model="form.content.icon" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          </div>
        </div>
        <div class="w-full px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
            <textarea v-model="form.content.description" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" rows="4"></textarea>
          </div>
        </div>
         <div class="w-full px-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Upload Gambar</label>
            <input type="file" @change="handleFileChange" ref="fileInput" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
          </div>
          <div v-if="form.content.image_url" class="mt-4"> <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
            <img :src="getFullImageUrl(form.content.image_url)" class="w-48 h-auto rounded shadow-lg"/>
          </div>
        </div>
      </div>

      <hr class="mt-6 border-b-1 border-blueGray-300" />
      <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Daftar Poin Keunggulan</h6>
      <div v-for="(item, index) in form.content.list_items" :key="index" class="flex flex-wrap items-center mb-4 border-b pb-4">
        <div class="w-full lg:w-5/12 px-4">
            <label class="block text-blueGray-600 text-xs font-bold mb-2">Ikon Poin {{ index + 1 }}</label>
            <input type="text" v-model="item.icon" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow w-full"/>
        </div>
        <div class="w-full lg:w-6/12 px-4">
            <label class="block text-blueGray-600 text-xs font-bold mb-2">Teks Poin {{ index + 1 }}</label>
            <input type="text" v-model="item.text" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow w-full"/>
        </div>
        <div class="w-full lg:w-1/12 px-4 mt-4 lg:mt-0"> <button @click.prevent="removeListItem(index)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow">
                <i class="fas fa-trash"></i>
            </button>
        </div>
      </div>

      <div class="w-full px-4 mt-4">
          <button @click.prevent="addListItem" class="bg-blueGray-700 text-white font-bold text-xs px-4 py-2 rounded shadow">
              <i class="fas fa-plus mr-2"></i> Tambah Poin
          </button>
      </div>
      <hr class="mt-6 border-b-1 border-blueGray-300" />
      <div class="w-full px-4 mt-6">
        <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow" type="submit">
          Simpan Perubahan
        </button>
      </div>

    </form>
  </div>
  <div v-else class="text-center py-10 text-blueGray-500"> Memuat data promo section...
  </div>
</template>

<script>
import axios from "axios";
const API_URL = 'http://localhost:8000/api/content-block/promo-section';

export default {
  data() {
    return {
      form: {
        content: {
          title: undefined, // Keep undefined for initial v-if check
          description: "",
          icon: "",
          image_url: "",
          list_items: [],
        }
      },
      selectedFile: null,
    };
  },
  methods: {
    getFullImageUrl(path) {
        if (!path) return '';
        if (path.startsWith('http')) return path;
        const baseUrl = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
        return `${baseUrl}/storage/${path}`;
    },
    handleFileChange(event) {
        this.selectedFile = event.target.files[0];
    },
    addListItem() {
        if (!this.form.content.list_items) {
            this.form.content.list_items = [];
        }
        this.form.content.list_items.push({ icon: 'fas fa-check', text: '' });
    },
    removeListItem(index) {
        this.form.content.list_items.splice(index, 1);
    },
    async fetchContent() {
      try {
        const response = await axios.get(API_URL);
        const blockData = response.data.data;

        if (blockData.content && !Array.isArray(blockData.content.list_items)) {
            blockData.content.list_items = [];
        }
        this.form = {
            ...blockData,
            content: {
                title: blockData.content?.title || "",
                description: blockData.content?.description || "",
                icon: blockData.content?.icon || "",
                image_url: blockData.content?.image_url || "",
                list_items: blockData.content?.list_items || [],
            }
        };

      } catch (error) {
        console.error("Gagal memuat data:", error);
        if (error.response && error.response.status === 404) {
            alert("Data promo section belum ada di database.");
             this.form.content.title = ""; // Ensure title is set to avoid infinite loading on 404
        } else {
             alert("Gagal memuat data yang ada.");
        }
      }
    },
    async saveContent() {
      const formData = new FormData();
      
      // Ensure list_items is always an array before stringifying
       const contentToSave = {
         ...this.form.content,
         list_items: Array.isArray(this.form.content.list_items) ? this.form.content.list_items : []
       };
      formData.append('content', JSON.stringify(contentToSave));
      
      if (this.selectedFile) {
        formData.append('image_file', this.selectedFile);
      }
      
      // Assuming POST updates the specific block based on URL key 'promo-section'
      // Add _method: 'PUT' if your backend expects it for updates via POST
      // formData.append('_method', 'PUT');

      try {
        const response = await axios.post(API_URL, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        alert(response.data.message || 'Berhasil disimpan!');
        
        // Update local data from response or re-fetch
        if (response.data.data) {
           const updatedBlock = response.data.data;
           if (updatedBlock.content && !Array.isArray(updatedBlock.content.list_items)) {
               updatedBlock.content.list_items = [];
           }
           this.form = {
               ...updatedBlock,
               content: {
                   title: updatedBlock.content?.title || "",
                   description: updatedBlock.content?.description || "",
                   icon: updatedBlock.content?.icon || "",
                   image_url: updatedBlock.content?.image_url || "",
                   list_items: updatedBlock.content?.list_items || [],
               }
           };
        } else {
           await this.fetchContent(); // Fallback
        }

        this.selectedFile = null;
        if (this.$refs.fileInput) {
             this.$refs.fileInput.value = '';
        }
      } catch (error) {
        console.error("Gagal menyimpan data:", error.response?.data || error.message);
        alert(`Gagal menyimpan data! ${error.response?.data?.message || ''}`);
      }
    },
  },
  mounted() {
    this.fetchContent();
  },
};
</script>