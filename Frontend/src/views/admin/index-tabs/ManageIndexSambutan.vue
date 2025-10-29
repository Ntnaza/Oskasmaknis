<template>
  <div>
    <form @submit.prevent="saveContent">
      <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6">Informasi Sambutan</h6>
      <div class="flex flex-wrap">
        
        <div class="w-full lg:w-6/12">
          <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Ketua OSIS</h6>
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua OSIS</label>
            <input type="text" v-model="contentBlock.content.ketua_osis_name" class="form-input-underline"/>
          </div>
          <div class="relative w-full mb-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua OSIS</label>
            <input type="file" @change="handleOsisFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full"/>
            <img v-if="osisPreviewUrl || currentOsisImageUrl" :src="osisPreviewUrl || currentOsisImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
          </div>
          <div class="relative w-full mb-3">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua OSIS</label>
            <textarea rows="4" v-model="contentBlock.content.ketua_osis_sambutan" class="form-textarea-underline"></textarea>
          </div>
          <div class="relative w-full mb-8 mt-8">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Program Kerja Unggulan OSIS</label>
            <div v-for="(programId, index) in contentBlock.content.featured_work_program_ids" :key="index" class="flex items-center mb-2">
              <Listbox v-model="contentBlock.content.featured_work_program_ids[index]" class="flex-1">
               <div class="relative">
                  <ListboxButton class="relative w-full cursor-pointer border-b-2 border-blueGray-200 py-2 pl-1 pr-10 text-left text-lg focus:outline-none focus:border-emerald-500 transition-all">
                    <span class="block truncate">{{ workProgramsForSelection.find(p => p.id === programId)?.title || '-- Pilih Program Kerja --' }}</span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"><ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" /></span>
                  </ListboxButton>
                  <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                      <ListboxOption v-for="program in workProgramsForSelection" :key="program.id" :value="program.id" v-slot="{ active, selected }" as="template">
                        <li :class="[active ? 'bg-emerald-100 text-emerald-900' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-10 pr-4']">
                          <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{ program.title }}</span>
                          <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-emerald-600"><CheckIcon class="h-5 w-5" aria-hidden="true" /></span>
                        </li>
                      </ListboxOption>
                    </ListboxOptions>
                  </transition>
                </div>
              </Listbox>
              <button @click.prevent="removeFeaturedProgram(index)" type="button" class="ml-2 bg-red-500 text-white font-bold uppercase text-xs px-3 py-1 rounded shadow">Hapus</button>
            </div>
            <button @click.prevent="addFeaturedProgram" type="button" class="mt-2 bg-blueGray-700 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow">+ Tambah Program</button>
          </div>
        </div>
        
        <div class="w-full lg:w-6/12 lg:pl-4">
           <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Ketua MPK</h6>
           <div class="relative w-full mb-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua MPK</label>
             <input type="text" v-model="contentBlock.content.ketua_mpk_name" class="form-input-underline"/>
           </div>
           <div class="relative w-full mb-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua MPK</label>
             <input type="file" @change="handleMpkFileChange" accept="image/*" class="text-sm border rounded px-3 py-2 w-full"/>
             <img v-if="mpkPreviewUrl || currentMpkImageUrl" :src="mpkPreviewUrl || currentMpkImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
           </div>
           <div class="relative w-full mb-3">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua MPK</label>
             <textarea rows="4" v-model="contentBlock.content.ketua_mpk_sambutan" class="form-textarea-underline"></textarea>
           </div>
           <div class="relative w-full mb-3 mt-8">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Visi MPK</label>
             <textarea rows="4" v-model="contentBlock.content.ketua_mpk_visi" class="form-textarea-underline"></textarea>
           </div>
           <div class="relative w-full mb-3">
             <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Misi MPK</label>
             <textarea rows="4" v-model="contentBlock.content.ketua_mpk_misi" class="form-textarea-underline"></textarea>
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
import { useWorkProgramStore } from '@/stores/workProgram.js';
import Swal from 'sweetalert2';
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

const CONTENT_BLOCK_KEY = 'index-sambutan-ketua';
const backendUrl = 'http://localhost:8000';

export default {
  name: "ManageIndexSambutan",
  components: {
    Listbox, ListboxButton, ListboxOptions, ListboxOption, CheckIcon, ChevronUpDownIcon
  },
  setup() {
    const contentBlockStore = useContentBlockStore();
    const workProgramStore = useWorkProgramStore();

    const contentBlock = ref({
      id: null,
      content: {
        title: '', ketua_osis_name: '', ketua_osis_sambutan: '', ketua_mpk_name: '',
        ketua_mpk_sambutan: '', ketua_osis_image_path: null, ketua_mpk_image_path: null,
        featured_work_program_ids: [], ketua_mpk_visi: '', ketua_mpk_misi: '',
      }
    });

    const osisFile = ref(null);
    const mpkFile = ref(null);
    const osisPreviewUrl = ref(null);
    const mpkPreviewUrl = ref(null);

    const currentOsisImageUrl = computed(() => {
      const path = contentBlock.value.content.ketua_osis_image_path;
      return path ? `${backendUrl}/storage/${path}` : null;
    });
    const currentMpkImageUrl = computed(() => {
      const path = contentBlock.value.content.ketua_mpk_image_path;
      return path ? `${backendUrl}/storage/${path}` : null;
    });
    const workProgramsForSelection = computed(() => {
        return workProgramStore.workProgramsForSelection;
    });

    const handleOsisFileChange = (event) => {
      const file = event.target.files[0];
      if (file) { osisFile.value = file; osisPreviewUrl.value = URL.createObjectURL(file); }
    };
    const handleMpkFileChange = (event) => {
      const file = event.target.files[0];
      if (file) { mpkFile.value = file; mpkPreviewUrl.value = URL.createObjectURL(file); }
    };

    const addFeaturedProgram = () => {
      if (!Array.isArray(contentBlock.value.content.featured_work_program_ids)) {
        contentBlock.value.content.featured_work_program_ids = [];
      }
      contentBlock.value.content.featured_work_program_ids.push(null);
    };
    const removeFeaturedProgram = (index) => {
      contentBlock.value.content.featured_work_program_ids.splice(index, 1);
    };

    const fetchData = async () => {
      try {
        // === PERBAIKAN DI SINI ===
        // 1. Panggil fungsi yang benar
        await contentBlockStore.fetchBlocksByPage('index');
        
        // 2. Ambil data dari store
        const blockData = contentBlockStore.blocks[CONTENT_BLOCK_KEY];
        // =========================
        
        if (blockData) {
          const fetchedContent = blockData.content || {};
          contentBlock.value = {
            ...blockData, 
            content: {
              ...contentBlock.value.content, 
              ...fetchedContent,
              featured_work_program_ids: Array.isArray(fetchedContent.featured_work_program_ids) ? fetchedContent.featured_work_program_ids : [],
            }
          };
        } else {
          console.warn(`Data untuk ${CONTENT_BLOCK_KEY} tidak ditemukan di store.`);
        }
        
        await workProgramStore.fetchWorkProgramsForSelection();
      } catch (error) {
         console.error(`Gagal memuat data ${CONTENT_BLOCK_KEY}:`, error);
         Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data sambutan.' });
      }
    };

    const saveContent = async () => {
      try {
        const blockToUpdate = {
          id: contentBlock.value.id,
          section_key: CONTENT_BLOCK_KEY,
          content: contentBlock.value.content,
        };

        if (!blockToUpdate.id) {
           // Jika ID null, coba cari dari store (sebagai fallback jika fetch gagal)
           const storeBlock = contentBlockStore.blocks[CONTENT_BLOCK_KEY];
           if (storeBlock && storeBlock.id) {
             blockToUpdate.id = storeBlock.id;
           } else {
             throw new Error("ID content block tidak ditemukan.");
           }
        }

        const filesToUpload = {
          osisFile: osisFile.value,
          mpkFile: mpkFile.value,
        };

        await contentBlockStore.updateContentBlocksWithFiles([blockToUpdate], filesToUpload);

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Konten Sambutan Ketua berhasil diperbarui.',
          timer: 1500,
          showConfirmButton: false,
        }).then(async () => {
           osisFile.value = null; mpkFile.value = null;
           osisPreviewUrl.value = null; mpkPreviewUrl.value = null;
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
      osisFile,
      mpkFile,
      osisPreviewUrl,
      mpkPreviewUrl,
      currentOsisImageUrl,
      currentMpkImageUrl,
      handleOsisFileChange,
      handleMpkFileChange,
      addFeaturedProgram,
      removeFeaturedProgram,
      saveContent,
      workProgramsForSelection,
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