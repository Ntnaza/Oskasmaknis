<template>
  <div>
    <navbar />
    <main>
      <!-- Header Halaman -->
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
                  Tetap terhubung dengan informasi terbaru, pengumuman, dan dokumentasi setiap momen berharga dari kegiatan OSIS & MPK kami.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Daftar Konten -->
      <section class="relative py-20 bg-blueGray-100">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap -mt-48">
            <!-- Looping Artikel/Galeri -->
            <div v-for="article in articles" :key="article.id" class="w-full md:w-6/12 lg:w-4/12 px-4">
              <router-link :to="`/berita/${article.slug}`">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg transform hover:-translate-y-2 transition-transform duration-300">
                  <img
                    alt="..."
                    :src="getFullImageUrl(article.featured_image_path)"
                    class="w-full h-48 object-cover align-middle rounded-t-lg"
                  />
                  <div class="px-4 py-5 flex-auto">
                    <h6 class="text-xl font-semibold">{{ article.title }}</h6>
                    <p class="mt-2 mb-4 text-blueGray-500">
                      {{ article.excerpt }}
                    </p>
                    <div class="text-emerald-500 font-bold">
                      Baca Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </div>
                  </div>
                </div>
              </router-link>
            </div>
            
            <div v-if="!articles.length" class="w-full text-center py-12">
                <p class="text-blueGray-500">Memuat konten...</p>
            </div>
          </div>
        </div>
      </section>

    </main>
    <footer-component />
  </div>
</template>
<script>
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

export default {
  data() {
    return {
      articles: [],
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
    async fetchArticles() {
      try {
        const response = await axios.get('http://localhost:8000/api/articles');
        this.articles = response.data;
      } catch (error) {
        console.error("Gagal mengambil data artikel:", error);
      }
    }
  },
  mounted() {
    this.fetchArticles();
  },
};
</script>

