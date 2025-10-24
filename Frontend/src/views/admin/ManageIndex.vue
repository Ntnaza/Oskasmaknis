<template>
  <div class="flex-auto px-4 lg:px-10 py-10">
    <div class="mb-8 border-b border-blueGray-200"> <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
        <li v-for="tab in tabs" :key="tab.id" class="mr-2 last:mr-0" role="presentation">
          <a
            @click="activeTab = tab.id"
            class="inline-block px-4 py-3 rounded-t-lg border-b-2 font-semibold uppercase cursor-pointer transition-all duration-300 relative group"
            :class="{
              'text-emerald-600': activeTab === tab.id,
              'text-blueGray-500 hover:text-blueGray-600': activeTab !== tab.id
            }"
            role="tab"
          >
            {{ tab.name }}
             <span
               class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 bg-emerald-600 transition-all duration-300"
               :class="[activeTab === tab.id ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
             ></span>
          </a>
        </li>
      </ul>
    </div>

    <form @submit.prevent="saveAllContent">
      <div v-show="activeTab === 'hero'">
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Dasar</h6>
        <div class="relative w-full mb-8">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
          <input type="text" v-model="pageContent.index.title" class="form-input-underline"/>
        </div>
        <div class="relative w-full mb-3">
          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
          <textarea rows="4" v-model="pageContent.index.subtitle" class="form-textarea-underline"></textarea>
        </div>
      </div>

      <div v-show="activeTab === 'ketua'">
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Sambutan</h6>
        <div class="flex flex-wrap">
          <div class="w-full lg:w-6/12 px-4">
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua OSIS</label>
              <input type="text" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_osis_name" class="form-input-underline"/>
            </div>
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua OSIS</label>
              <input type="file" @change="handleOsisFileChange" accept="image/*" class="text-sm"/>
              <img v-if="osisPreviewUrl || currentOsisImageUrl" :src="osisPreviewUrl || currentOsisImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
            </div>
            <div class="relative w-full mb-3">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua OSIS</label>
              <textarea rows="4" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_osis_sambutan" class="form-textarea-underline"></textarea>
            </div>
            <div class="relative w-full mb-8 mt-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Program Kerja Unggulan OSIS</label>
              <div
                v-for="(programId, index) in contentBlocks['index-sambutan-ketua'].content.featured_work_program_ids"
                :key="index"
                class="flex items-center mb-2"
              >
                <Listbox v-model="contentBlocks['index-sambutan-ketua'].content.featured_work_program_ids[index]" class="flex-1">
                  <div class="relative">
                    <ListboxButton
                      class="relative w-full cursor-pointer border-b-2 border-blueGray-200 py-2 pl-1 pr-10 text-left text-lg focus:outline-none focus:border-emerald-500 transition-all"
                    >
                      <span class="block truncate">
                        {{ workProgramsForSelection.find(p => p.id === programId)?.title || '-- Pilih Program Kerja --' }}
                      </span>
                      <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                      </span>
                    </ListboxButton>
                    <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                      <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                        <ListboxOption
                          v-for="program in workProgramsForSelection"
                          :key="program.id"
                          :value="program.id"
                          v-slot="{ active, selected }"
                          as="template"
                        >
                          <li :class="[active ? 'bg-emerald-100 text-emerald-900' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-10 pr-4']">
                            <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                              {{ program.title }}
                            </span>
                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-emerald-600">
                              <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                          </li>
                        </ListboxOption>
                      </ListboxOptions>
                    </transition>
                  </div>
                </Listbox>
                <button
                  @click="removeFeaturedProgram(index)"
                  type="button"
                  class="ml-2 bg-red-500 text-white font-bold uppercase text-xs px-3 py-1 rounded shadow"
                >
                  Hapus
                </button>
              </div>
              <button
                @click="addFeaturedProgram"
                type="button"
                class="mt-2 bg-blueGray-700 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow"
              >
                + Tambah Program
              </button>
            </div>
          </div>
          <div class="w-full lg:w-6/12 px-4">
             <div class="relative w-full mb-8">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Ketua MPK</label>
               <input type="text" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_mpk_name" class="form-input-underline"/>
             </div>
             <div class="relative w-full mb-8">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Ketua MPK</label>
               <input type="file" @change="handleMpkFileChange" accept="image/*" class="text-sm"/>
               <img v-if="mpkPreviewUrl || currentMpkImageUrl" :src="mpkPreviewUrl || currentMpkImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
             </div>
             <div class="relative w-full mb-3">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Sambutan Ketua MPK</label>
               <textarea rows="4" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_mpk_sambutan" class="form-textarea-underline"></textarea>
             </div>
             <div class="relative w-full mb-3 mt-8">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Visi MPK</label>
               <textarea rows="4" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_mpk_visi" class="form-textarea-underline"></textarea>
             </div>
             <div class="relative w-full mb-3">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Misi MPK</label>
               <textarea rows="4" v-model="contentBlocks['index-sambutan-ketua'].content.ketua_mpk_misi" class="form-textarea-underline"></textarea>
             </div>
           </div>
        </div>
      </div>

      <div v-show="activeTab === 'pembina'">
           <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Pembina</h6>
           <div class="flex flex-wrap">
             <div class="w-full px-4 mb-8">
               <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul Seksi</label>
               <input type="text" v-model="contentBlocks['index-pembina'].content.title" class="form-input-underline"/>
             </div>
             <div class="w-full lg:w-6/12 px-4">
               <div class="relative w-full mb-3">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Nama Pembina</label>
                 <input type="text" v-model="contentBlocks['index-pembina'].content.pembina_name" class="form-input-underline"/>
               </div>
             </div>
             <div class="w-full lg:w-6/12 px-4">
               <div class="relative w-full mb-3">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Jabatan Pembina</label>
                 <input type="text" v-model="contentBlocks['index-pembina'].content.pembina_title" class="form-input-underline"/>
               </div>
             </div>
             <div class="w-full px-4 mt-8">
               <div class="relative w-full mb-8">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Foto Pembina</label>
                 <input type="file" @change="handlePembinaFileChange" accept="image/*" class="text-sm"/>
                 <img v-if="pembinaPreviewUrl || currentPembinaImageUrl" :src="pembinaPreviewUrl || currentPembinaImageUrl" class="mt-4 rounded-lg max-w-200-px shadow-lg"/>
               </div>
             </div>
           </div>
      </div>

       <div v-show="activeTab === 'jurusan'">
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Informasi Kolaborasi Jurusan</h6>
        <div class="flex flex-wrap">
          <div class="w-full px-4">
            <div class="relative w-full mb-8">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
              <input type="text" v-model="contentBlocks['index-kolaborasi-jurusan'].content.title" class="form-input-underline"/>
            </div>
            <div class="relative w-full mb-3">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
              <textarea rows="4" v-model="contentBlocks['index-kolaborasi-jurusan'].content.description" class="form-textarea-underline"></textarea>
            </div>
          </div>
          <div class="w-full px-4 mt-4">
            <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Daftar Jurusan</label>
            <div v-for="(jurusan, index) in contentBlocks['index-kolaborasi-jurusan'].content.jurusan_list" :key="index" class="p-4 mb-4 border border-blueGray-200 rounded-lg">
              <div class="flex justify-between items-center mb-2">
                <p class="font-bold text-blueGray-700">Jurusan #{{ index + 1 }}</p>
                <button @click="removeJurusan(index)" type="button" class="bg-red-500 text-white font-bold uppercase text-xs px-3 py-1 rounded shadow">Hapus</button>
              </div>
              <div class="flex flex-wrap">
                <div class="w-full lg:w-6/12 px-2 mb-4"><label class="block text-blueGray-600 text-xs font-bold mb-2">Nama</label><input type="text" v-model="jurusan.name" class="form-input-underline"/></div>
                <div class="w-full lg:w-6/12 px-2 mb-4"><label class="block text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label><input type="text" v-model="jurusan.description" class="form-input-underline"/></div>
                <div class="w-full lg:w-6/12 px-2"><label class="block text-blueGray-600 text-xs font-bold mb-2">Nama File Logo</label><input type="text" v-model="jurusan.logo_filename" class="form-input-underline"/></div>
                <div class="w-full lg:w-6/12 px-2"><label class="block text-blueGray-600 text-xs font-bold mb-2">Warna</label><input type="text" v-model="jurusan.color" class="form-input-underline"/></div>
              </div>
            </div>
            <button @click="addJurusan" type="button" class="mt-2 bg-blueGray-700 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow">+ Tambah Jurusan</button>
          </div>
        </div>
      </div>

       <div v-show="activeTab === 'sejarah'">
           <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Seksi #7: Sejarah Singkat OSKA</h6>
            <div class="w-full px-4">
              <div class="relative w-full mb-8">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
                <input type="text" v-model="contentBlocks['index-sejarah-oska'].content.title" class="form-input-underline"/>
              </div>
              <div class="relative w-full mb-3">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Isi Sejarah</label>
                <textarea rows="4" v-model="contentBlocks['index-sejarah-oska'].content.description" class="form-textarea-underline"></textarea>
              </div>
            </div>
      </div>

       <div v-show="activeTab === 'ajakan'">
           <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">Seksi #8: Ajakan Bergabung</h6>
            <div class="w-full px-4">
              <div class="relative w-full mb-8">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
                <input type="text" v-model="contentBlocks['index-ajakan-bergabung'].content.title" class="form-input-underline"/>
              </div>
              <div class="relative w-full mb-3">
                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Isi Ajakan</label>
                <textarea rows="4" v-model="contentBlocks['index-ajakan-bergabung'].content.description" class="form-textarea-underline"></textarea>
              </div>
            </div>
      </div>


       <hr class="my-8 border-b-1 border-blueGray-200" />
       <div class="w-full px-4 flex justify-end">
         <button
           class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
           type="submit"
         >
           Simpan Semua Perubahan
         </button>
       </div>
    </form>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { usePageContentStore } from '@/stores/pageContent.js';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import { useWorkProgramStore } from '@/stores/workProgram.js';
import Swal from 'sweetalert2';
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

export default {
  components: {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    CheckIcon,
    ChevronUpDownIcon
  },
  setup() {
    const activeTab = ref('hero');
    const tabs = ref([
      { id: 'hero', name: 'Hero' },
      { id: 'ketua', name: 'Sambutan Ketua' },
      { id: 'pembina', name: 'Pembina' },
      { id: 'jurusan', name: 'Kolaborasi Jurusan' },
      { id: 'sejarah', name: 'Sejarah OSKA' },
      { id: 'ajakan', name: 'Ajakan Bergabung' },
    ]);

    const pageContentStore = usePageContentStore();
    const contentBlockStore = useContentBlockStore();
    const workProgramStore = useWorkProgramStore();

    const pageContent = ref({ index: { title: '', subtitle: '' } });
    const contentBlocks = ref({
      // Struktur awal tetap sama, ID akan ditambahkan saat fetch
      'index-sambutan-ketua': { id: null, content: { title: '', ketua_osis_name: '', ketua_osis_sambutan: '', ketua_mpk_name: '', ketua_mpk_sambutan: '', ketua_osis_image_path: null, ketua_mpk_image_path: null, featured_work_program_ids: [], ketua_mpk_visi: '', ketua_mpk_misi: '', } },
      'index-pembina': { id: null, content: { title: '', pembina_name: '', pembina_title: '', pembina_image_path: null } },
      'index-kolaborasi-jurusan': { id: null, content: { title: '', description: '', jurusan_list: [] } },
      'index-sejarah-oska': { id: null, content: { title: '', description: '' } },
      'index-ajakan-bergabung': { id: null, content: { title: '', description: '' } },
    });

    const backendUrl = 'http://localhost:8000';
    const osisFile = ref(null);
    const mpkFile = ref(null);
    const pembinaFile = ref(null);
    const osisPreviewUrl = ref(null);
    const mpkPreviewUrl = ref(null);
    const pembinaPreviewUrl = ref(null);

    // --- Computed Properties (Tidak berubah) ---
    const currentOsisImageUrl = computed(() => {/* ... */ const path = contentBlocks.value['index-sambutan-ketua']?.content.ketua_osis_image_path; return path ? `${backendUrl}/storage/${path}` : null; });
    const currentMpkImageUrl = computed(() => { /* ... */ const path = contentBlocks.value['index-sambutan-ketua']?.content.ketua_mpk_image_path; return path ? `${backendUrl}/storage/${path}` : null; });
    const currentPembinaImageUrl = computed(() => {/* ... */ const path = contentBlocks.value['index-pembina']?.content.pembina_image_path; return path ? `${backendUrl}/storage/${path}` : null; });
    const workProgramsForSelection = computed(() => { return workProgramStore.workProgramsForSelection; });

    // --- Methods (Tidak berubah, kecuali fetchData & saveAllContent) ---
    const handleOsisFileChange = (event) => { /* ... logika ... */ if (event.target.files[0]) { osisFile.value = event.target.files[0]; osisPreviewUrl.value = URL.createObjectURL(osisFile.value); } };
    const handleMpkFileChange = (event) => { /* ... logika ... */ if (event.target.files[0]) { mpkFile.value = event.target.files[0]; mpkPreviewUrl.value = URL.createObjectURL(mpkFile.value); } };
    const handlePembinaFileChange = (event) => { /* ... logika ... */ if (event.target.files[0]) { pembinaFile.value = event.target.files[0]; pembinaPreviewUrl.value = URL.createObjectURL(pembinaFile.value); } };
    const addJurusan = () => { /* ... logika ... */ contentBlocks.value['index-kolaborasi-jurusan'].content.jurusan_list.push({ name: '', description: '', logo_filename: 'logo.png', color: 'blueGray' }); };
    const removeJurusan = (index) => { /* ... logika ... */ contentBlocks.value['index-kolaborasi-jurusan'].content.jurusan_list.splice(index, 1); };
    const addFeaturedProgram = () => { /* ... logika ... */ if (!Array.isArray(contentBlocks.value['index-sambutan-ketua'].content.featured_work_program_ids)) { contentBlocks.value['index-sambutan-ketua'].content.featured_work_program_ids = []; } contentBlocks.value['index-sambutan-ketua'].content.featured_work_program_ids.push(null); };
    const removeFeaturedProgram = (index) => { /* ... logika ... */ contentBlocks.value['index-sambutan-ketua'].content.featured_work_program_ids.splice(index, 1); };

    // === PERBAIKAN FETCHDATA ===
    const fetchData = async () => {
        await pageContentStore.fetchContentBySlug('index');
        if (pageContentStore.content.index) {
            pageContent.value.index = { ...pageContentStore.content.index };
        }
        await contentBlockStore.fetchBlocksByPage('index');
        if (Object.keys(contentBlockStore.blocks).length) {
            for (const key in contentBlocks.value) {
                if (contentBlockStore.blocks[key]) {
                     // SIMPAN SELURUH OBJEK BLOCK (TERMASUK ID)
                     contentBlocks.value[key] = {
                         ...contentBlockStore.blocks[key], // Salin ID, section_key, dll.
                         // Gabungkan konten default dengan konten dari API
                         content: {
                             ...(contentBlocks.value[key].content || {}), // Ambil default jika ada
                             ...(contentBlockStore.blocks[key].content || {}) // Timpa dengan data API
                         }
                     };

                     // Normalisasi (tetap penting)
                     let currentContent = contentBlocks.value[key].content;
                     if (key === 'index-kolaborasi-jurusan' && !Array.isArray(currentContent.jurusan_list)) { currentContent.jurusan_list = []; }
                     if (key === 'index-sambutan-ketua' && !Array.isArray(currentContent.featured_work_program_ids)) { currentContent.featured_work_program_ids = []; }
                }
            }
        }
        await workProgramStore.fetchWorkProgramsForSelection();
    };

    // === PERBAIKAN SAVEALLCONTENT ===
     const saveAllContent = async () => {
        try {
            await pageContentStore.updatePageContent(pageContent.value);

            // Sertakan ID saat membuat array blocksToUpdate
            const blocksToUpdate = Object.keys(contentBlocks.value)
              .map(key => ({
                id: contentBlocks.value[key]?.id, // Ambil ID dari state
                section_key: key,
                content: contentBlocks.value[key].content
              }))
              .filter(block => block.id != null); // Pastikan hanya kirim block yang punya ID

            if (blocksToUpdate.length === 0) {
                 console.warn("Tidak ada content block valid (dengan ID) untuk diupdate.");
                 // Tampilkan pesan jika perlu
                 Swal.fire({ icon: 'info', title: 'Info', text: 'Tidak ada data konten block yang perlu disimpan.' });
                 return;
            }

            const filesToUpload = { osisFile: osisFile.value, mpkFile: mpkFile.value, pembinaFile: pembinaFile.value };
            await contentBlockStore.updateContentBlocksWithFiles(blocksToUpdate, filesToUpload);

            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Konten halaman Index berhasil diperbarui.', timer: 1500, showConfirmButton: false })
            .then(async () => {
                osisFile.value = null; mpkFile.value = null; pembinaFile.value = null;
                osisPreviewUrl.value = null; mpkPreviewUrl.value = null; pembinaPreviewUrl.value = null;
                await fetchData(); // Refresh data
            });
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menyimpan data.' });
            console.error("Error saving content:", error.response?.data || error);
        }
     };

    onMounted(fetchData);

    // Return values (tidak berubah)
    return {
      activeTab, tabs, pageContent, contentBlocks, osisFile, mpkFile, pembinaFile,
      osisPreviewUrl, mpkPreviewUrl, pembinaPreviewUrl, currentOsisImageUrl,
      currentMpkImageUrl, currentPembinaImageUrl, handleOsisFileChange, handleMpkFileChange,
      handlePembinaFileChange, addJurusan, removeJurusan, addFeaturedProgram,
      removeFeaturedProgram, saveAllContent, workProgramsForSelection, workProgramStore
    };
  }
}
</script>

<style scoped>
/* Style tetap sama */
.form-input-underline {
  display: block; width: 100%; font-size: 1.125rem;
  padding-left: 0.125rem; padding-right: 0.125rem;
  border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-input-underline:focus {
  outline: none; box-shadow: none; border-color: #10B981;
}
.form-textarea-underline {
  display: block; width: 100%; font-size: 1rem;
  padding-left: 0.125rem; padding-right: 0.125rem;
  border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0;
  background-color: transparent; transition: border-color .15s ease-in-out;
}
.form-textarea-underline:focus {
  outline: none; box-shadow: none; border-color: #10B981;
}
</style>