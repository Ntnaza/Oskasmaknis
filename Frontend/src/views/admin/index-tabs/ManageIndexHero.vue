<template>
  <form @submit.prevent="saveContent">
    <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">
      Informasi Dasar
    </h6>

    <div class="flex flex-wrap">
      <div class="w-full">
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
          <input type="text" v-model="pageContent.index.title" class="form-input-underline"/>
        </div>
      </div>
      <div class="w-full">
        <div class="relative w-full mb-3">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
          <textarea rows="4" v-model="pageContent.index.subtitle" class="form-textarea-underline"></textarea>
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
    </div>
  </form>
</template>

<script>
import { ref, onMounted } from 'vue';
import { usePageContentStore } from '@/stores/pageContent.js';
import Swal from 'sweetalert2';

export default {
  name: "ManageIndexHero",
  setup() {
    // Inisialisasi store
    const pageContentStore = usePageContentStore();
    
    // State lokal untuk form
    const pageContent = ref({
      index: { title: '', subtitle: '' }
    });

    // Fungsi untuk mengambil data
    const fetchData = async () => {
      try {
        await pageContentStore.fetchContentBySlug('index');
        if (pageContentStore.content.index) {
          // Salin data dari store ke state lokal
          pageContent.value.index = { ...pageContentStore.content.index };
        }
      } catch (error) {
        console.error("Gagal memuat data Hero:", error);
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data.' });
      }
    };

    // Fungsi untuk menyimpan data
    const saveContent = async () => {
      try {
        // Kirim hanya data yang relevan (pageContent.value)
        await pageContentStore.updatePageContent(pageContent.value);
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Konten Hero berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false,
        });
      } catch (error) {
        console.error("Error saving content:", error.response?.data || error);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Terjadi kesalahan saat menyimpan data.',
        });
      }
    };

    // Panggil fetchData saat komponen dimuat
    onMounted(fetchData);

    // Return semua yang dibutuhkan oleh template
    return {
      pageContent,
      saveContent,
    };
  }
}
</script>

<style scoped>
/* Style untuk input underline */
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