<template>
  <div>
    <navbar />
    <main>
      <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75">
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          style="background-image: url('https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80');"
        >
          <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
        </div>
        <div class="container relative mx-auto">
          <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
              <div>
                <h1 class="text-white font-semibold text-5xl">
                  Berita & Galeri Kegiatan
                </h1>
                <p class="mt-4 text-lg text-blueGray-200">
                  Tetap terhubung dengan informasi terbaru dari kegiatan OSIS & MPK kami.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <section class="relative py-20 bg-blueGray-100">
        <div class="container mx-auto px-4">
          <div class="-mt-48">
            
            <div 
              v-if="articles.length > 0 && paginationData" 
              class="masonry-container" 
              :key="paginationData.current_page"
            >
              <div
                v-for="article in articles"
                :key="article.id"
                class="masonry-item"
              >
                <router-link :to="`/berita/${article.slug}`">
                  <img
                    :src="getFullImageUrl(article.featured_image_path)"
                    :alt="article.title"
                    class="w-full h-auto block"
                  />
                  <div class="p-4">
                    <h3 class="text-lg font-bold text-blueGray-700 mb-2">{{ article.title }}</h3>
                    <p class="text-sm text-blueGray-500 leading-relaxed">{{ article.excerpt }}</p>
                    
                    <hr class="my-4 border-blueGray-200" /> 
                    
                    <div class="text-xs text-blueGray-400 font-medium mt-4">
                      <span>{{ new Date(article.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                    </div>

                  </div>
                </router-link>
              </div>
            </div>

            <div v-if="paginationData" class="flex justify-center mt-12">
              <button 
                @click="fetchArticles(paginationData.prev_page_url)"
                :disabled="!paginationData.prev_page_url"
                class="bg-emerald-500 text-white font-bold py-2 px-4 rounded-l disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                &laquo; Sebelumnya
              </button>
              <button 
                @click="fetchArticles(paginationData.next_page_url)"
                :disabled="!paginationData.next_page_url"
                class="bg-emerald-500 text-white font-bold py-2 px-4 rounded-r disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                Berikutnya &raquo;
              </button>
            </div>

            <div v-if="isLoading" class="text-center py-12">
              <p class="text-blueGray-500">Memuat konten...</p>
            </div>
             <div v-if="!isLoading && articles.length === 0" class="w-full text-center py-12">
              <p class="text-blueGray-500">Saat ini belum ada artikel untuk ditampilkan.</p>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer-component />
  </div>
</template>

<script>
// Bagian script tidak perlu diubah
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

const API_URL = 'http://localhost:8000/api/articles';

export default {
  data() {
    return {
      articles: [],
      isLoading: true,
      paginationData: null,
    };
  },
  components: {
    Navbar,
    FooterComponent,
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/600x400/E2E8F0/A0AEC0?text=Gambar';
      if (path.startsWith('http')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchArticles(url = API_URL) {
      this.isLoading = true;
      try {
        const response = await axios.get(url);
        this.articles = response.data.data;
        this.paginationData = response.data;
      } catch (error) {
        console.error("Gagal mengambil data artikel:", error);
      } finally {
        this.isLoading = false;
      }
    }
  },
  mounted() {
    this.fetchArticles();
  },
};
</script>

<style scoped>
/* Style Masonry dengan penyesuaian */
.masonry-container {
  column-gap: 1rem;
  column-count: 2;
}
.masonry-item {
  display: inline-block;
  width: 100%;
  margin-bottom: 1rem;
  break-inside: avoid;
  background-color: white;
  /* PERUBAHAN DI SINI: Sudut lebih tajam */
  border-radius: 0.75rem; /* Sebelumnya 1rem */
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: box-shadow 0.3s ease;
}
.masonry-item:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
@media (min-width: 768px) {
  .masonry-container {
    column-count: 3;
  }
}
</style>