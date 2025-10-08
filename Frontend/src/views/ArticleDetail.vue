<template>
  <!-- 1. Menambahkan satu div pembungkus utama -->
  <div>
    <!-- 2. Blok v-if sekarang membungkus semua konten utama -->
    <div v-if="article">
      <navbar />
      <main>
        <!-- Header dengan Gambar Utama -->
        <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75">
          <div
            class="absolute top-0 w-full h-full bg-center bg-cover"
            :style="{ backgroundImage: 'url(' + getFullImageUrl(article.featured_image_path) + ')' }"
          >
            <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
          </div>
          <div class="container relative mx-auto">
            <div class="items-center flex flex-wrap">
              <div class="w-full lg:w-10/12 px-4 ml-auto mr-auto text-center">
                <div>
                  <h1 class="text-white font-semibold text-4xl md:text-5xl">
                    {{ article.title }}
                  </h1>
                  <p class="mt-4 text-lg text-blueGray-200">
                    Dipublikasikan pada {{ formatDate(article.published_at) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Konten Artikel & Galeri -->
        <section class="relative py-20 bg-blueGray-100">
          <div class="container mx-auto px-4 -mt-48">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg p-6 md:p-12">
              
              <!-- Isi Konten Artikel (jika ada) -->
              <div v-if="article.content" class="prose max-w-none mb-8" v-html="article.content"></div>

              <!-- Bagian Galeri (jika ada) -->
              <div v-if="article.gallery && article.gallery.length > 0">
                <hr class="my-8 border-b-1 border-blueGray-200" />
                <h3 class="text-2xl font-semibold mb-6 text-blueGray-700">Galeri Kegiatan</h3>
                <div class="flex flex-wrap -mx-2">
                  <div v-for="(imagePath, index) in article.gallery" :key="index" class="w-1/2 md:w-1/3 lg:w-1/4 p-2">
                    <img 
                      :src="getFullImageUrl(imagePath)" 
                      @click="openLightbox(index)"
                      alt="Galeri Foto" 
                      class="w-full h-40 object-cover rounded-lg shadow-md cursor-pointer transform hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      </main>
      <footer-component />
      
      <!-- Lightbox untuk Galeri -->
      <div v-if="lightbox.show" @click="closeLightbox" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4 transition-opacity duration-300">
          <button @click.stop="prevImage" class="absolute left-4 text-white text-4xl opacity-75 hover:opacity-100">&lt;</button>
          <img :src="lightbox.imageUrl" @click.stop class="max-h-full max-w-full object-contain rounded-lg shadow-2xl"/>
          <button @click.stop="nextImage" class="absolute right-4 text-white text-4xl opacity-75 hover:opacity-100">&gt;</button>
          <button @click.stop="closeLightbox" class="absolute top-4 right-4 text-white text-4xl opacity-75 hover:opacity-100">&times;</button>
      </div>
    </div>
    <!-- 3. Blok v-else sekarang menjadi saudara kandung dari blok v-if -->
    <div v-else class="flex items-center justify-center h-screen">
      <p class="text-2xl text-blueGray-500">Memuat konten...</p>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

export default {
  name: "ArticleDetail",
  components: {
    Navbar,
    FooterComponent,
  },
  data() {
    return {
      article: null,
      lightbox: {
          show: false,
          imageUrl: '',
          currentIndex: 0
      }
    };
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('id-ID', options);
    },
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/1200x800/E2E8F0/A0AEC0?text=Gambar';
      if (path.startsWith('http')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchArticle() {
      const slug = this.$route.params.slug;
      try {
        const response = await axios.get(`http://localhost:8000/api/articles/${slug}`);
        this.article = response.data;
      } catch (error) {
        console.error("Gagal mengambil data artikel:", error);
        this.$router.push('/'); 
      }
    },
    openLightbox(index){
        this.lightbox.currentIndex = index;
        this.lightbox.imageUrl = this.getFullImageUrl(this.article.gallery[index]);
        this.lightbox.show = true;
    },
    closeLightbox(){
        this.lightbox.show = false;
    },
    nextImage(){
        let nextIndex = this.lightbox.currentIndex + 1;
        if(nextIndex >= this.article.gallery.length) {
            nextIndex = 0;
        }
        this.openLightbox(nextIndex);
    },
    prevImage(){
        let prevIndex = this.lightbox.currentIndex - 1;
        if(prevIndex < 0) {
            prevIndex = this.article.gallery.length - 1;
        }
        this.openLightbox(prevIndex);
    }
  },
  created() {
    this.fetchArticle();
  },
};
</script>

