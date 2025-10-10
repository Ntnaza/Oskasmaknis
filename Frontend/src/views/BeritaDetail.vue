<template>
  <div>
    <navbar />
    <main class="profile-page">
      <section v-if="isLoading" class="relative block min-h-screen-75">
        <div class="absolute top-0 w-full h-full bg-center bg-cover bg-blueGray-200"></div>
      </section>
      
      <div v-if="article">
        <section class="relative block min-h-screen-75">
          <div
            class="absolute top-0 w-full h-full bg-cover header-background-image"
            :style="{ backgroundImage: `url('${getFullImageUrl(article.featured_image_path)}')` }"
          >
            <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
          </div>
        </section>

        <section class="relative py-16 bg-blueGray-100">
          <div class="container mx-auto px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
              <div class="px-6">
                <div class="text-center mt-12">
                  <h1 class="text-4xl font-bold leading-normal mb-2 text-blueGray-700">
                    {{ article.title }}
                  </h1>
                  <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                    Oleh: {{ article.author ? article.author.name : 'Admin' }} | 
                    {{ article.published_at ? new Date(article.published_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '' }}
                  </div>
                </div>

                <div class="mt-10 py-10 border-t border-blueGray-200">
                  <div class="flex flex-wrap justify-center">
                    <div class="w-full lg:w-9/12 px-4">
                      <div class="prose max-w-none mb-8" v-html="article.content"></div>
                      
                      <div v-if="article.gallery && article.gallery.length > 0">
                        <hr class="my-8 border-blueGray-200" />
                        <h2 class="text-2xl font-semibold mb-4">Galeri Foto</h2>
                        
                        <div class="masonry-container">
                          <div 
                            v-for="(imagePath, index) in article.gallery" 
                            :key="index" 
                            @click="selectedImage = getFullImageUrl(imagePath)"
                            class="masonry-item"
                          >
                            <img 
                              :src="getFullImageUrl(imagePath)" 
                              :alt="'Galeri foto ' + (index + 1)"
                              class="w-full h-auto rounded-md block"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      
      <div v-if="!isLoading && !article" class="text-center py-24">
         <h2 class="text-2xl font-semibold">Artikel tidak ditemukan</h2>
         <p class="text-blueGray-500">Artikel yang Anda cari mungkin telah dihapus atau URL-nya salah.</p>
      </div>
    </main>
    <footer-component />

    <div 
      v-if="selectedImage" 
      id="gallery-modal"
      @click="selectedImage = null"
    >
      <img :src="selectedImage" alt="Gambar Galeri Diperbesar" class="modal-image" @click.stop />
      <button @click="selectedImage = null" class="modal-close-button">&times;</button>
    </div>

  </div>
</template>

<script>
// ... Bagian script tidak perlu diubah ...
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

export default {
  name: "berita-detail",
  components: {
    Navbar,
    FooterComponent,
  },
  data() {
    return {
      article: null,
      isLoading: true,
      selectedImage: null,
    };
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/1920x1080/E2E8F0/A0AEC0?text=Gambar';
      if (path.startsWith('http')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchArticle() {
      this.isLoading = true;
      const slug = this.$route.params.slug;
      try {
        const response = await axios.get(`http://localhost:8000/api/articles/${slug}`);
        this.article = response.data;
      } catch (error) {
        console.error("Gagal mengambil data artikel:", error);
        this.article = null;
      } finally {
        this.isLoading = false;
      }
    },
  },
  mounted() {
    this.fetchArticle();
  },
};
</script>

<style>
/* ... Aturan Masonry dan Modal (tetap sama) ... */
.masonry-container {
  column-gap: 1rem;
  column-count: 2;
}
.masonry-item {
  display: inline-block;
  width: 100%;
  margin-bottom: 1rem;
  break-inside: avoid;
  cursor: pointer;
  border-radius: 1rem;
  overflow: hidden;
}
.masonry-item img {
  width: 100%;
  height: auto;
  display: block;
}
@media (min-width: 768px) {
  .masonry-container {
    column-count: 3;
  }
}

#gallery-modal {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  background-color: rgba(0, 0, 0, 0.75) !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  z-index: 99999 !important;
  opacity: 1 !important;
  visibility: visible !important;
}
#gallery-modal .modal-image {
  max-width: 90vw !important;
  max-height: 90vh !important;
  object-fit: contain !important;
}
#gallery-modal .modal-close-button {
  position: absolute !important;
  top: 1rem !important;
  right: 1.5rem !important;
  color: white !important;
  font-size: 3rem !important;
  font-weight: bold !important;
  cursor: pointer !important;
}

/* ======================================================= */
/* ATURAN BARU UNTUK MEMAKSA FOKUS GAMBAR HEADER             */
/* ======================================================= */
.header-background-image {
  background-position: center !important; /* Default untuk mobile */
}

@media (min-width: 1024px) { /* lg breakpoint */
  .header-background-image {
    background-position: top !important; /* Override untuk desktop */
  }
}
</style>