<template>
  <div>
    <index-navbar />
    <section class="header relative pt-16 items-center flex flex-col sm:flex-row h-auto sm:h-screen max-h-860-px overflow-hidden"> 
      <div class="container mx-auto items-center flex flex-wrap">
        <div class="w-full md:w-8/12 lg:w-6/12 xl:w-6/12 px-4 z-10">
          <div class="pt-32 sm:pt-0">
            <h2 class="font-semibold text-4xl text-blueGray-600">
              {{ pageContentStore.content.index?.title || "Memuat..." }}
            </h2>
            <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
              {{ pageContentStore.content.index?.subtitle || "Harap tunggu sebentar..." }}
            </p>
            <div class="mt-12">
              <router-link to="/landing" class="get-started text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-emerald-500 active:bg-emerald-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150">
                Jelajahi disini
              </router-link>
              <router-link to="/berita-dan-galeri" class="github-star ml-1 text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150">
                Berita Terbaru
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="relative sm:absolute sm:top-0 sm:right-0 w-full sm:w-6/12 h-auto sm:h-full flex items-center justify-center mt-12 sm:mt-0 pt-0 sm:pt-16 sm:-mt-20">
  <div class="relative w-72 h-72"> 
  <img
    v-for="(logo, index) in logos"
    :key="logo.alt"
    :src="logo.src"
    :alt="logo.alt"
    class="absolute inset-0 w-full h-full object-contain transition-all duration-1000 ease-in-out"
    :class="{
      'opacity-100 scale-100 z-10': index === activeLogoIndex, // Logo Aktif
      // Logo Tidak Aktif: Geseran disesuaikan sedikit karena container lebih kecil
      'opacity-40 scale-85 z-0 transform translate-x-20 translate-y-10': index !== activeLogoIndex 
    }"
    style="transition-delay: 50ms;" 
  />
</div>
</div>
    </section>

    <section class="mt-48 md:mt-40 pb-40 relative bg-blueGray-50">
      <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0);">
        <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
          <polygon class="text-blueGray-50 fill-current" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
      <div class="container mx-auto">
        <div class="flex flex-wrap items-center">
          <div class="w-10/12 md:w-6/12 lg:w-4/12 px-12 md:px-4 mr-auto ml-auto -mt-32">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-emerald-500">
              <img
                alt="Foto Ketua OSIS"
                :src="contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_image_path
                  ? `${backendUrl}/storage/${contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_image_path}`
                  : 'https://placehold.co/700x800?text=Foto+Ketua+OSIS'"
                class="w-full align-middle rounded-t-lg h-auto object-cover"
              />
              <blockquote class="relative p-8 mb-4">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-95-px -top-94-px">
                  <polygon points="-30,95 583,95 583,65" class="text-emerald-500 fill-current"></polygon>
                </svg>
                <h4 class="text-xl font-bold text-white">{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_name || '...' }}</h4>
                <p class="text-md font-light mt-2 text-white">"{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_osis_sambutan || '...' }}"</p>
              </blockquote>
            </div>
          </div>
          <div class="w-full md:w-6/12 px-4">
            <h4 class="text-2xl font-semibold mb-4 text-blueGray-700">Program Kerja Unggulan OSIS</h4>
            <div class="flex flex-wrap">
              <div v-if="featuredPrograms.length > 0" class="w-full flex flex-wrap">
                <div v-for="program in featuredPrograms" :key="program.id" class="w-full lg:w-6/12 px-4">
                  <div class="relative flex flex-col mt-4">
                    <div class="px-4 py-5 flex-auto">
                      <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                        <i class="fas fa-tasks"></i>
                      </div>
                      <h6 class="text-xl mb-1 font-semibold">{{ program.title }}</h6>
                      <p class="mb-4 text-blueGray-500">{{ program.description }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="w-full px-4">
                <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-600">
                  Belum ada program kerja unggulan OSIS yang ditampilkan.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 relative bg-blueGray-100">
      <div class="container mx-auto">
        <div class="flex flex-wrap items-center">
           <div class="w-full md:w-6/12 px-4">
              <div class="relative flex flex-col mt-4">
                <div class="px-4 py-5 flex-auto">
                  <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                    <i class="fas fa-eye"></i>
                  </div>
                  <h6 class="text-xl mb-1 font-semibold">Visi MPK</h6>
                  <p class="mb-4 text-blueGray-500">
                    {{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_visi || 'Visi MPK belum diatur.' }}
                  </p>
                </div>
              </div>

              <div class="relative flex flex-col mt-4">
                <div class="px-4 py-5 flex-auto">
                  <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-white">
                    <i class="fas fa-bullseye"></i>
                  </div>
                  <h6 class="text-xl mb-1 font-semibold">Misi MPK</h6>
                  <p class="mb-4 text-blueGray-500">
                    {{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_misi || 'Misi MPK belum diatur.' }}
                  </p>
                </div>
              </div>
           </div>
           <div class="w-10/12 md:w-6/12 lg:w-4/12 px-12 md:px-4 mr-auto ml-auto">
             <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-lightBlue-500">
              <img
                alt="Foto Ketua MPK"
                :src="contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_image_path
                  ? `${backendUrl}/storage/${contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_image_path}`
                  : 'https://placehold.co/700x800?text=Foto+Ketua+MPK'"
                class="w-full align-middle rounded-t-lg h-auto object-cover"
              />
              <blockquote class="relative p-8 mb-4">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-95-px -top-94-px">
                  <polygon points="-30,95 583,95 583,65" class="text-lightBlue-500 fill-current"></polygon>
                </svg>
                <h4 class="text-xl font-bold text-white">{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_name || '...' }}</h4>
                <p class="text-md font-light mt-2 text-white">"{{ contentBlockStore.blocks['index-sambutan-ketua']?.content.ketua_mpk_sambutan || '...' }}"</p>
              </blockquote>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="pb-20 pt-20 relative block bg-blueGray-50">
  <div class="container mx-auto px-4">
    <div class="flex flex-wrap justify-center text-center mb-12">
      <div class="w-full lg:w-6/12 px-4">
        <h2 class="text-4xl font-semibold text-blueGray-700">
          {{ contentBlockStore.blocks['index-pembina']?.content.title || 'Dibimbing Oleh' }}
        </h2>
      </div>
    </div>

    <div class="flex flex-wrap items-center justify-center">
      <div class="w-full md:w-4/12 lg:w-3/12 px-4 mb-8 md:mb-0">
        <img
          alt="Foto Pembina"
          :src="contentBlockStore.blocks['index-pembina']?.content.pembina_image_path
            ? `${backendUrl}/storage/${contentBlockStore.blocks['index-pembina'].content.pembina_image_path}`
            : 'https://demos.creative-tim.com/vue-notus/img/team-1-800x800.jpg'"
          class="mx-auto w-48 h-48 md:w-52 md:h-52 object-contain"
        />
      </div>
      <div class="w-full md:w-5/12 lg:w-4/12 px-4 text-center md:text-left">
        <h5 class="text-2xl font-bold text-blueGray-700">
          {{ contentBlockStore.blocks['index-pembina']?.content.pembina_name || 'Nama Pembina' }}
        </h5>
        <p class="mt-1 text-lg text-blueGray-500 uppercase font-semibold">
          {{ contentBlockStore.blocks['index-pembina']?.content.pembina_title || 'Jabatan Pembina' }}
        </p>
      </div>
    </div>
  </div>
</section>  

    <section class="block relative z-1 bg-blueGray-600">
      <div class="container mx-auto px-4 py-24">
        <div class="justify-center flex flex-wrap">
          <div class="w-full lg:w-12/12 px-4">
            <div class="flex flex-wrap">
              <div class="w-full text-center lg:w-8/12 mx-auto mb-12">
                <h3 class="font-semibold text-3xl text-white">Berita & Galeri Terbaru</h3>
                <p class="text-blueGray-300 text-lg leading-relaxed mt-4">
                  Ikuti kegiatan dan informasi terbaru dari OSIS & MPK melalui berita dan galeri kegiatan kami.
                </p>
              </div>
              <div v-if="articleStore.latestArticles && articleStore.latestArticles.length > 0" class="w-full flex flex-wrap justify-center">
                <div v-for="article in articleStore.latestArticles" :key="article.id" class="w-full lg:w-4/12 px-4">
                  <router-link :to="'/berita/' + article.slug">
                    <div class="hover:-mt-4 relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg ease-linear transition-all duration-150">
                      <img
                        alt="Gambar Berita"
                        class="align-middle border-none max-w-full h-auto rounded-t-lg object-cover" style="height: 250px"
                        :src="article.image_url || 'https://placehold.co/600x400'"
                      />
                      <div class="px-4 py-5 flex-auto">
                        <h5 class="text-xl font-semibold pb-4 truncate">
                          {{ article.title }}
                        </h5>
                        <p class="text-blueGray-500 mb-4 h-24 overflow-hidden">
                          {{ article.excerpt }}
                        </p>
                        <span class="text-xs font-semibold text-blueGray-400">
                          {{ new Date(article.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                      </div>
                    </div>
                  </router-link>
                </div>
              </div>
              <div v-else class="w-full text-center text-blueGray-300">
                <p>Memuat berita terbaru...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center">
          <div class="w-full md:w-6/12 px-4 ml-auto mr-auto mt-32">
            <div class="justify-center flex flex-wrap relative">
              <div class="my-4 w-full lg:w-6/12 px-4">
                <template v-if="contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.jurusan_list">
                  <a href="javascript:void(0);" v-for="(jurusan) in contentBlockStore.blocks['index-kolaborasi-jurusan'].content.jurusan_list.slice(0, 3)" :key="jurusan.name">
                    <div :class="[jurusan.color, 'shadow-lg rounded-lg text-center p-8 mt-8']">
                      <img alt="Logo Jurusan" class="max-w-full w-24 h-24 object-contain mx-auto" :src="`/assets/img/jurusan/${jurusan.logo_filename}`"/>
                      <p class="text-lg text-white mt-4 font-semibold">{{ jurusan.name }}</p>
                    </div>
                  </a>
                </template>
              </div>
              <div class="my-4 w-full lg:w-6/12 px-4 lg:mt-16">
                 <template v-if="contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.jurusan_list">
                  <a href="javascript:void(0);" v-for="(jurusan) in contentBlockStore.blocks['index-kolaborasi-jurusan'].content.jurusan_list.slice(3, 6)" :key="jurusan.name">
                    <div :class="[jurusan.color, 'shadow-lg rounded-lg text-center p-8 mt-8']">
                      <img alt="Logo Jurusan" class="max-w-full w-24 h-24 object-contain mx-auto" :src="`/assets/img/jurusan/${jurusan.logo_filename}`"/>
                      <p class="text-lg text-white mt-4 font-semibold">{{ jurusan.name }}</p>
                    </div>
                  </a>
                </template>
              </div>
            </div>
          </div>
          <div class="w-full md:w-4/12 px-12 md:px-4 ml-auto mr-auto mt-48">
            <div class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-white">
              <i class="fas fa-drafting-compass text-xl"></i>
            </div>
            <h3 class="text-3xl mb-2 font-semibold leading-normal">
              {{ contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.title || '...' }}
            </h3>
            <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-600">
              {{ contentBlockStore.blocks['index-kolaborasi-jurusan']?.content.description || '...' }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-blueGray-600 overflow-hidden">
      <div class="container mx-auto pb-64">
        <div class="flex flex-wrap justify-center">
          <div class="w-full md:w-5/12 px-12 md:px-4 ml-auto mr-auto md:mt-64">
            <div
              class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-white"
            >
              <i class="fas fa-landmark text-xl"></i>
            </div>
            <h3 class="text-3xl mb-2 font-semibold leading-normal text-white">
              {{ contentBlockStore.blocks['index-sejarah-oska']?.content.title || 'Sejarah Singkat OSKA' }}
            </h3>
            <p
              class="text-lg font-light leading-relaxed mt-4 mb-4 text-blueGray-400"
            >
              {{ contentBlockStore.blocks['index-sejarah-oska']?.content.description || 'Memuat sejarah...' }}
            </p>
            <router-link
              to="/profile"
              class="mt-4 inline-block text-white font-bold px-6 py-4 rounded outline-none focus:outline-none mr-1 mb-1 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150"
            >
              Baca Lebih Lanjut
            </router-link>
          </div>

          <div class="w-full md:w-4/12 px-4 mr-auto ml-auto mt-32 relative">
            <img
  :src="'/assets/img/logo-oska.png'" alt="Logo OSKA"
  class="absolute top-0 right-0 left-0 w-full h-auto max-w-full opacity-50"
  style="z-index: 0; transform: translateY(-50px);" />
          </div>
        </div>
      </div>
    </section>

    <section class="pb-16 bg-blueGray-200 relative pt-32">
      <div
        class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20"
        style="transform: translateZ(0);"
      >
        <svg
          class="absolute bottom-0 overflow-hidden"
          xmlns="http://www.w3.org/2000/svg"
          preserveAspectRatio="none"
          version="1.1"
          viewBox="0 0 2560 100"
          x="0"
          y="0"
        >
          <polygon
            class="text-blueGray-200 fill-current"
            points="2560 0 2560 100 0 100"
          ></polygon>
        </svg>
      </div>

      <div class="container mx-auto">
        <div
          class="flex flex-wrap justify-center bg-white shadow-xl rounded-lg -mt-64 py-16 px-12 relative z-10"
        >
          <div class="w-full text-center lg:w-8/12">
            <p class="text-4xl text-center">
              <span role="img" aria-label="love">
                😍
              </span>
            </p>
            <h3 class="font-semibold text-3xl">
              {{ contentBlockStore.blocks['index-ajakan-bergabung']?.content.title || '...' }}
            </h3>
            <p class="text-blueGray-500 text-lg leading-relaxed mt-4 mb-4">
              {{ contentBlockStore.blocks['index-ajakan-bergabung']?.content.description || '...' }}
            </p>
            <div class="sm:block flex flex-col mt-10">
              <router-link
  to="/landing"
  class="get-started text-white font-bold px-4 py-3 rounded outline-none focus:outline-none mr-1 mb-2 bg-blueGray-700 active:bg-blueGray-600 uppercase text-sm shadow hover:shadow-lg ease-linear transition-all duration-150"
>
  Cari Tahu Lebih Lanjut
</router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer-component />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePageContentStore } from '@/stores/pageContent.js';
import { useContentBlockStore } from '@/stores/contentBlock.js';
import { useWorkProgramStore } from '@/stores/workProgram.js';
import { useArticleStore } from '@/stores/article.js';

// eslint-disable-next-line no-unused-vars
import IndexNavbar from "@/components/Navbars/IndexNavbar.vue";
// eslint-disable-next-line no-unused-vars
import FooterComponent from "@/components/Footers/Footer.vue";

// eslint-disable-next-line no-unused-vars
const backendUrl = 'http://localhost:8000';

const pageContentStore = usePageContentStore();
const contentBlockStore = useContentBlockStore();
const workProgramStore = useWorkProgramStore();
const articleStore = useArticleStore();

const logos = ref([
  { src: '/assets/img/logo-oska.png', alt: 'Logo OSKA' },
  { src: '/assets/img/logo-sekolah.png', alt: 'Logo Sekolah' }, // Pastikan path ini benar
]);

const activeLogoIndex = ref(0);
let intervalId = null;

onMounted(() => {
  pageContentStore.fetchContentBySlug('index');
  contentBlockStore.fetchBlocksByPage('index');
  workProgramStore.fetchWorkPrograms();
  articleStore.fetchLatestArticles();

  intervalId = setInterval(() => {
    activeLogoIndex.value = (activeLogoIndex.value + 1) % logos.value.length;
  }, 3000);
});

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId);
  }
});

// eslint-disable-next-line no-unused-vars
const featuredPrograms = computed(() => {
  const allPrograms = workProgramStore.workPrograms;
  const blockData = contentBlockStore.blocks['index-sambutan-ketua'];

  if (!allPrograms || allPrograms.length === 0 || !blockData || !blockData.content.featured_work_program_ids || !Array.isArray(blockData.content.featured_work_program_ids)) {
    return [];
  }
  const featuredIds = blockData.content.featured_work_program_ids;
  return allPrograms.filter(program => featuredIds.includes(program.id));
});
</script>