<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-blueGray-700 text-xl font-bold">Kelola Bagian Promo Landing</h6>
        </div>
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
                <div v-if="form.content.image_url">
                  <p class="text-sm text-blueGray-600 mb-2">Gambar Saat Ini:</p>
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
              <div class="w-full lg:w-1/12 px-4 mt-4">
                  <button @click.prevent="removeListItem(index)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow">
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
        <div v-else class="text-center py-10 text-blueGray-500">
             Memuat data promo section... 
        </div>
      </div>
    </div>
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
          // ======================================================
          // ==    INISIALISASI 'title' JADI UNDEFINED UNTUK v-if  ==
          // ======================================================
          title: undefined, // <-- Ubah dari "" menjadi undefined
          // ======================================================
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
        return `http://localhost:8000/storage/${path}`;
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
        // Penting: Backend Anda (setelah diperbaiki) mengembalikan { data: block }
        const blockData = response.data.data; 

        // Pastikan list_items selalu array
        if (blockData.content && !Array.isArray(blockData.content.list_items)) {
            blockData.content.list_items = [];
        }
        // Pastikan semua properti content ada (atau beri default)
        this.form = {
            ...blockData, // Salin properti lain seperti id, section_key
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
        // Tambahkan feedback error yang lebih jelas jika perlu
        // Misalnya, jika error 404 karena data belum ada di DB
        if (error.response && error.response.status === 404) {
            alert("Data promo section belum ada di database. Silakan buat melalui seeder atau manual.");
            // Set form ke state default agar tidak error
             this.form.content.title = ""; // Set title agar v-if lolos setelah error 404
        } else {
             alert("Gagal memuat data yang ada.");
        }
      }
    },
    async saveContent() {
      const formData = new FormData();
      
      // Kirim seluruh objek konten sebagai string JSON
      formData.append('content', JSON.stringify(this.form.content));
      
      if (this.selectedFile) {
        formData.append('image_file', this.selectedFile);
      }
      
      // ======================================================
      // ==   PERBAIKAN SAVE: KIRIM KE CONTROLLER YANG TEPAT ==
      // ==      DAN GUNAKAN METODE YANG SESUAI (PUT/POST)    ==
      // ======================================================
      // Kita perlu mengirim ke route update yang benar.
      // Jika Anda HANYA mengupdate content block ini (bukan bulk),
      // Anda perlu route dan controller method yang berbeda (misal PUT /api/content-block/{key})
      // Untuk SEMENTARA, kita asumsikan Anda pakai POST ke endpoint yang sama (meskipun kurang ideal)
      // formData.append('_method', 'PUT'); // Jika backend mengharapkan PUT

      try {
        // Ganti URL jika diperlukan (misal, jika update pakai ID atau key)
        const response = await axios.post(API_URL, formData, { 
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        alert(response.data.message || 'Berhasil disimpan!');
        
        // Update data setelah save (bisa fetch ulang atau dari response)
        if (response.data.data) {
             // Pastikan struktur response.data.data sesuai
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
            await this.fetchContent(); // Fallback fetch ulang
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