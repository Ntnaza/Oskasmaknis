<template>
  <div>
    <form @submit.prevent="saveContent">
      <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">Ajakan Bergabung</h6>
      <div class="flex flex-wrap">
        <div class="w-full">
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
            <input type="text" v-model="contentBlock.content.title" class="form-input-underline"/>
          </div>
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Isi Ajakan</label>
            <textarea rows="4" v-model="contentBlock.content.description" class="form-textarea-underline"></textarea>
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
import { ref, onMounted } from 'vue';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import Swal from 'sweetalert2';

const CONTENT_BLOCK_KEY = 'index-ajakan-bergabung';

export default {
  name: "ManageIndexAjakan",
  setup() {
    const contentBlockStore = useContentBlockStore();

    // State lokal hanya untuk blok ini
    const contentBlock = ref({
      id: null,
      content: {
        title: '',
        description: '',
      }
    });

    // --- Methods ---
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
         Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data ajakan bergabung.' });
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
          text: 'Konten Ajakan Bergabung berhasil diperbarui.',
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