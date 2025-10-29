<template>
  <div>
    <form @submit.prevent="saveContent">
      <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">Informasi Kolaborasi Jurusan</h6>
      <div class="flex flex-wrap">
        <div class="w-full">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
            <input type="text" v-model="contentBlock.content.title" class="form-input-underline"/>
          </div>
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
            <textarea rows="4" v-model="contentBlock.content.description" class="form-textarea-underline"></textarea>
          </div>
        </div>
        <div class="w-full mt-4">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Daftar Jurusan</label>
          <div v-for="(jurusan, index) in contentBlock.content.jurusan_list" :key="index" class="p-4 mb-4 border border-blueGray-200 rounded-lg">
            <div class="flex justify-between items-center mb-2">
              <p class="font-bold text-blueGray-700">Jurusan #{{ index + 1 }}</p>
              <button @click.prevent="removeJurusan(index)" type="button" class="bg-red-500 text-white font-bold uppercase text-xs px-3 py-1 rounded shadow">Hapus</button>
            </div>
            <div class="flex flex-wrap">
              <div class="w-full lg:w-6/12 px-2 mb-4"><label class="block text-blueGray-600 text-xs font-bold mb-2">Nama</label><input type="text" v-model="jurusan.name" class="form-input-underline"/></div>
              <div class="w-full lg:w-6/12 px-2 mb-4"><label class="block text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label><input type="text" v-model="jurusan.description" class="form-input-underline"/></div>
              <div class="w-full lg:w-6/12 px-2"><label class="block text-blueGray-600 text-xs font-bold mb-2">Nama File Logo</label><input type="text" v-model="jurusan.logo_filename" class="form-input-underline"/></div>
              <div class="w-full lg:w-6/12 px-2"><label class="block text-blueGray-600 text-xs font-bold mb-2">Warna</label><input type="text" v-model="jurusan.color" class="form-input-underline"/></div>
            </div>
          </div>
          <button @click.prevent="addJurusan" type="button" class="mt-2 bg-blueGray-700 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow">+ Tambah Jurusan</button>
        </div>
      </div>

      <hr class="my-8 border-b-1 border-blueGray-200" />
      <div class="w-full flex justify-end">
        <button
          class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
          type="submit"
        >
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import Swal from 'sweetalert2';

const CONTENT_BLOCK_KEY = 'index-kolaborasi-jurusan';

export default {
  name: "ManageIndexJurusan",
  setup() {
    const contentBlockStore = useContentBlockStore();

    // State lokal hanya untuk blok ini
    const contentBlock = ref({
      id: null,
      content: {
        title: '',
        description: '',
        jurusan_list: []
      }
    });

    // --- Methods ---
    const addJurusan = () => {
      if (!Array.isArray(contentBlock.value.content.jurusan_list)) {
        contentBlock.value.content.jurusan_list = [];
      }
      contentBlock.value.content.jurusan_list.push({
        name: '',
        description: '',
        logo_filename: 'nama-file.png',
        color: 'bg-blueGray-500'
      });
    };
    const removeJurusan = (index) => {
      contentBlock.value.content.jurusan_list.splice(index, 1);
    };

    // Fungsi Fetch Data
    const fetchData = async () => {
      try {
        await contentBlockStore.fetchBlocksByPage('index');
        const blockData = contentBlockStore.blocks[CONTENT_BLOCK_KEY];
        
        if (blockData) {
          const fetchedContent = blockData.content || {};
          contentBlock.value = {
            ...blockData, 
            content: {
              ...contentBlock.value.content, 
              ...fetchedContent,
              jurusan_list: Array.isArray(fetchedContent.jurusan_list) ? fetchedContent.jurusan_list : [],
            }
          };
        } else {
          console.warn(`Data untuk ${CONTENT_BLOCK_KEY} tidak ditemukan di store.`);
        }
      } catch (error) {
         console.error(`Gagal memuat data ${CONTENT_BLOCK_KEY}:`, error);
         Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data kolaborasi jurusan.' });
      }
    };

    // Fungsi Save Data
    const saveContent = async () => {
      try {
        const blockToUpdate = {
          id: contentBlock.value.id,
          section_key: CONTENT_BLOCK_KEY,
          content: contentBlock.value.content,
        };

        if (!blockToUpdate.id) {
           const storeBlock = contentBlockStore.blocks[CONTENT_BLOCK_KEY];
           if (storeBlock && storeBlock.id) {
             blockToUpdate.id = storeBlock.id;
           } else {
             throw new Error("ID content block tidak ditemukan.");
           }
        }

        // Tidak ada file upload di tab ini
        await contentBlockStore.updateContentBlocksWithFiles([blockToUpdate], {});

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Konten Kolaborasi Jurusan berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false,
        }).then(async () => {
           await fetchData(); // Refresh data
        });
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Terjadi kesalahan saat menyimpan data.',
        });
        console.error("Error saving content:", error.response?.data || error);
      }
    };

    onMounted(fetchData);

    return {
      contentBlock,
      addJurusan,
      removeJurusan,
      saveContent,
    };
  }
}
</script>

<style scoped>
.form-input-underline {
  display: block; width: 100%; font-size: 1rem;
  padding-left: 0.125rem; padding-right: 0.125rem;
  padding-top: 0.5rem; padding-bottom: 0.5rem;
  border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-input-underline:focus {
  outline: none; box-shadow: none; border-color: #10B981;
}
.form-textarea-underline {
  display: block; width: 100%; font-size: 1rem;
  padding-left: 0.125rem; padding-right: 0.125rem;
  padding-top: 0.5rem; padding-bottom: 0.5rem;
  border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-textarea-underline:focus {
  outline: none; box-shadow: none; border-color: #10B981;
}
</style>