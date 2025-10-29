<template>
  <div>
    <form @submit.prevent="saveContent">
      <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">Informasi Pembina</h6>
      <div class="flex flex-wrap">
        <div class="w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul Seksi</label>
          <input type="text" v-model="contentBlock.content.title" class="form-input-underline"/>
        </div>
        <div class="w-full lg:w-6/12">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Pembina</label>
            <input type="text" v-model="contentBlock.content.pembina_name" class="form-input-underline"/>
          </div>
        </div>
        <div class="w-full lg:w-6/12 lg:pl-4">
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Jabatan Pembina</label>
            <input type="text" v-model="contentBlock.content.pembina_title" class="form-input-underline"/>
          </div>
        </div>
        <div class="w-full mt-8">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Pembina</label>
            <input type="file" @change="handlePembinaFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full"/>
            <img v-if="pembinaPreviewUrl || currentPembinaImageUrl" :src="pembinaPreviewUrl || currentPembinaImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
          </div>
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
import { ref, onMounted, computed } from 'vue';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import Swal from 'sweetalert2';

const CONTENT_BLOCK_KEY = 'index-pembina';
const backendUrl = 'http://localhost:8000';

export default {
  name: "ManageIndexPembina",
  setup() {
    const contentBlockStore = useContentBlockStore();

    // State lokal hanya untuk blok ini
    const contentBlock = ref({
      id: null,
      content: {
        title: '',
        pembina_name: '',
        pembina_title: '',
        pembina_image_path: null
      }
    });

    const pembinaFile = ref(null);
    const pembinaPreviewUrl = ref(null);

    // --- Computed Properties ---
    const currentPembinaImageUrl = computed(() => {
      const path = contentBlock.value.content.pembina_image_path;
      return path ? `${backendUrl}/storage/${path}` : null;
    });

    // --- Methods ---
    const handlePembinaFileChange = (event) => {
      const file = event.target.files[0];
      if (file) {
        pembinaFile.value = file;
        pembinaPreviewUrl.value = URL.createObjectURL(file);
      }
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
            }
          };
        } else {
          console.warn(`Data untuk ${CONTENT_BLOCK_KEY} tidak ditemukan di store.`);
        }
      } catch (error) {
         console.error(`Gagal memuat data ${CONTENT_BLOCK_KEY}:`, error);
         Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data pembina.' });
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

        const filesToUpload = {
          pembinaFile: pembinaFile.value,
        };

        await contentBlockStore.updateContentBlocksWithFiles([blockToUpdate], filesToUpload);

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Konten Pembina berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false,
        }).then(async () => {
           pembinaFile.value = null;
           pembinaPreviewUrl.value = null;
           await fetchData();
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
      pembinaFile,
      pembinaPreviewUrl,
      currentPembinaImageUrl,
      handlePembinaFileChange,
      saveContent,
    };
  }
}
</script>

<style scoped>
.form-input-underline {
  display: block; width: 100%; font-size: 1.125rem;
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