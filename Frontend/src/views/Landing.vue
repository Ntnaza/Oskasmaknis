<template>
  <div>
    <navbar />
    <main>
      <div
        class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75"
      >
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          :style="{ backgroundImage: 'url(' + heroImageUrl + ')' }"
        >
          <span
            id="blackOverlay"
            class="w-full h-full absolute opacity-75 bg-black"
          ></span>
        </div>
        <div class="container relative mx-auto">
          <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
              <div>
                <h1 v-if="pageContent.title" class="text-white font-semibold text-5xl">
                  {{ pageContent.title }}
                </h1>
                <p v-if="pageContent.subtitle" class="mt-4 text-lg text-blueGray-200">
                  {{ pageContent.subtitle }}
                </p>
                <p v-else-if="!pageContent.title" class="mt-4 text-lg text-blueGray-200">Memuat...</p>
              </div>
            </div>
          </div>
        </div>
        <div
          class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
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
      </div>

      <section class="pb-20 bg-blueGray-200 -mt-24">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap">
            <template v-if="features.length > 0">
              <div v-for="feature in features" :key="feature.id" class="w-full md:w-4/12 px-4 text-center"
                   :class="feature.order === 2 ? 'md:pt-6' : 'lg:pt-12 pt-6'">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                  <div class="px-4 py-5 flex-auto">
                    <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full"
                         :class="`bg-${feature.color}-400`">
                      <i :class="feature.icon"></i>
                    </div>
                    <h6 class="text-xl font-semibold">{{ feature.title }}</h6>
                    <p class="mt-2 mb-4 text-blueGray-500">{{ feature.description }}</p>
                  </div>
                </div>
              </div>
            </template>
            <div v-else class="w-full text-center py-12">
                <p class="text-blueGray-500">Memuat fitur...</p>
            </div>
          </div>
        </div>
      </section>

      <section v-if="promoContent" class="relative py-20 bg-blueGray-200"> <div class="container mx-auto px-4">
          <div class="items-center flex flex-wrap">
            <div class="w-full md:w-4/12 ml-auto mr-auto px-4">
              <img alt="..." class="max-w-full rounded-lg shadow-lg" :src="promoImageUrl" />
            </div>
            <div class="w-full md:w-5/12 ml-auto mr-auto px-4">
              <div class="md:pr-12">
                <div class="text-emerald-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-emerald-300">
                  <i :class="promoContent.content?.icon" class="text-xl"></i>
                </div>
                <h3 class="text-3xl font-semibold text-blueGray-800">{{ promoContent.content?.title }}</h3>
                <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
                  {{ promoContent.content?.description }}
                </p>
                <ul class="list-none mt-6">
                  <li v-for="(item, index) in promoContent.content?.list_items" :key="index" class="py-2">
                    <div class="flex items-center">
                      <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200 mr-3">
                          <i :class="item.icon"></i>
                        </span>
                      </div>
                      <div>
                        <h4 class="text-blueGray-500">{{ item.text }}</h4>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
       <section v-else class="py-20 bg-blueGray-200"> <div class="container mx-auto px-4 text-center">
               <p class="text-blueGray-500">Memuat promo...</p>
           </div>
       </section>

      <section class="pt-20 pb-48 relative bg-white">
         <div class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20 h-20" style="transform: translateZ(0);">
          <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
            <polygon class="text-white fill-current" points="2560 0 2560 100 0 100"></polygon>
          </svg>
        </div>
        <canvas ref="particleCanvas" class="absolute top-0 left-0 w-full h-full z-0"></canvas> <div class="container mx-auto px-4 relative z-10">
          <div class="flex flex-wrap justify-center text-center mb-24">
            <div class="w-full lg:w-6/12 px-4">
              <h2 class="text-4xl font-semibold text-blueGray-700">Struktur Inti Organisasi</h2>
              <p class="text-lg leading-relaxed m-4 text-blueGray-500">
                Berikut adalah anggota inti dari OSIS & MPK periode saat ini yang berdedikasi untuk memajukan sekolah.
              </p>
            </div>
          </div>
          <div class="flex flex-wrap justify-center">
            <template v-if="teamMembers.length > 0">
              <div v-for="member in teamMembers" :key="member.id" class="w-full md:w-6/12 lg:w-5/12 xl:w-4/12 mb-12 px-4">
                <router-link :to="`/anggota/${member.id}`">
                  <div class="px-6 py-8 text-center transform hover:-translate-y-2 transition-transform duration-300">
                    <img
                      alt="..."
                      :src="getFullImageUrl(member.photo_path)"
                      class="mx-auto max-w-200-px"
                    />
                    <div class="pt-6">
                      <h5 class="text-xl font-bold text-blueGray-700">{{ member.name }}</h5>
                      <p class="mt-1 text-sm text-blueGray-400 uppercase font-semibold">
                        {{ member.position }}
                      </p>
                    </div>
                  </div>
                </router-link>
              </div>
            </template>
            <div v-else class="w-full text-center py-12">
                <p class="text-blueGray-500">Memuat anggota tim...</p>
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

const PAGE_API_URL = 'http://localhost:8000/api/page-content/landing';
const FEATURE_API_URL = 'http://localhost:8000/api/features';
const PROMO_API_URL = 'http://localhost:8000/api/content-block/promo-section';
const TEAM_API_URL = 'http://localhost:8000/api/team-members';
const BASE_STORAGE_URL = 'http://localhost:8000/storage/';

export default {
  data() {
    return {
      pageContent: { title: null, subtitle: null, hero_image_url: null },
      features: [],
      promoContent: null,
      teamMembers: [],
      // Properti baru untuk menyimpan observer & animasi
      particleIntersectionObserver: null,
      particleResizeObserver: null,
      particleAnimationFrameId: null
    };
  },
  computed: {
    heroImageUrl() {
      if (this.pageContent && this.pageContent.hero_image_url) {
        const path = this.pageContent.hero_image_url;
        return path.startsWith('http') ? path : `${BASE_STORAGE_URL}${path}`;
      }
      return '';
    },
    promoImageUrl() {
      if (this.promoContent?.content?.image_url) {
        const path = this.promoContent.content.image_url;
        return path.startsWith('http') ? path : `${BASE_STORAGE_URL}${path}`;
      }
      return 'https://placehold.co/600x400/E2E8F0/A0AEC0?text=Upload+Gambar';
    },
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/200x200/E2E8F0/A0AEC0?text=Foto';
      return path.startsWith('http') ? path : `${BASE_STORAGE_URL}${path}`;
    },
    getSocialColor(platform) {
        const colors = {
            twitter: 'bg-lightBlue-400', facebook: 'bg-lightBlue-600',
            instagram: 'bg-pink-500', dribbble: 'bg-pink-500',
            github: 'bg-blueGray-700', google: 'bg-red-600',
        };
        return colors[platform.toLowerCase()] || 'bg-blueGray-700';
    },
    
    // === FUNGSI INITPARTICLES YANG DIPERBARUI ===
    initParticles() {
      const canvas = this.$refs.particleCanvas;
      if (!canvas || !canvas.parentElement) return; // Pastikan parent ada
      
      const ctx = canvas.getContext('2d');
      let scale = window.devicePixelRatio || 1;
      
      const setupCanvas = () => {
        if (!canvas.parentElement) return;
        canvas.width = canvas.parentElement.offsetWidth * scale;
        canvas.height = canvas.parentElement.offsetHeight * scale;
        ctx.scale(scale, scale);
      }

      let particles = [];
      const shapes = ['circle', 'square', 'triangle'];
      const colors = ['#4285F4', '#DB4437', '#F4B400', '#0F9D58']; 
      const particleCount = 20;

      class Particle {
        constructor() {
          this.shape = shapes[Math.floor(Math.random() * shapes.length)];
          this.color = colors[Math.floor(Math.random() * colors.length)];
          // Pastikan canvas punya ukuran saat partikel dibuat
          const scaledWidth = canvas.width / scale || window.innerWidth;
          const scaledHeight = canvas.height / scale || window.innerHeight;
          this.x = Math.random() * scaledWidth;
          this.y = Math.random() * scaledHeight;
          
          const baseSize = Math.random() * 8 + 4;
          this.size = baseSize;
          this.minSize = baseSize * 0.9;
          this.maxSize = baseSize * 1.1;
          this.sizeDirection = Math.random() > 0.5 ? 1 : -1;

          this.speedX = Math.random() * 0.3 - 0.15;
          this.speedY = Math.random() * 0.3 - 0.15;
          this.rotation = Math.random() * Math.PI * 2;
          this.rotationSpeed = Math.random() * 0.01 - 0.005;
        }
        update() {
          this.x += this.speedX;
          this.y += this.speedY;
          this.rotation += this.rotationSpeed;

          this.size += this.sizeDirection * 0.02;
          if (this.size > this.maxSize || this.size < this.minSize) {
            this.sizeDirection *= -1;
          }
          
          const scaledWidth = canvas.width / scale;
          const scaledHeight = canvas.height / scale;
          if (this.x > scaledWidth + this.size || this.x < -this.size) this.speedX *= -1;
          if (this.y > scaledHeight + this.size || this.y < -this.size) this.speedY *= -1;
        }
        draw() {
          ctx.save();
          ctx.translate(this.x, this.y);
          ctx.rotate(this.rotation);
          ctx.fillStyle = this.color;
          ctx.beginPath();
          
          switch (this.shape) {
            case 'circle':
              ctx.arc(0, 0, this.size, 0, Math.PI * 2);
              break;
            case 'square':
              ctx.rect(-this.size / 2, -this.size / 2, this.size, this.size);
              break;
            case 'triangle':
              ctx.moveTo(0, -this.size / 2);
              ctx.lineTo(this.size / 2, this.size / 2);
              ctx.lineTo(-this.size / 2, this.size / 2);
              break;
          }

          ctx.closePath();
          ctx.fill();
          ctx.restore();
        }
      }

      const createParticles = () => {
        particles = [];
        // Cek jika canvas sudah punya ukuran
        if (canvas.width > 0 && canvas.height > 0) {
          for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
          }
        }
      }
      
      const animateParticles = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < particles.length; i++) {
          particles[i].update();
          particles[i].draw();
        }
        // Gunakan 'this.' untuk menyimpan ID
        this.particleAnimationFrameId = requestAnimationFrame(animateParticles);
      }
      
      // --- PEMBAGIAN TUGAS OBSERVER ---

      // 1. Intersection Observer: Hanya untuk play/pause animasi
      this.particleIntersectionObserver = new IntersectionObserver((entries) => {
          if (entries[0].isIntersecting) {
            animateParticles(); // Mulai animasi
          } else {
            cancelAnimationFrame(this.particleAnimationFrameId); // Hentikan animasi
          }
      }, { threshold: 0.01 });

      this.particleIntersectionObserver.observe(canvas.parentElement);
      
      // 2. Resize Observer: Hanya untuk memperbaiki ukuran canvas & partikel
      // Ini adalah SOLUSI untuk bug "tertarik"
      this.particleResizeObserver = new ResizeObserver(() => {
        // Setiap kali ukuran <section> berubah (karena gambar di-load, dll)
        // kita setup ulang canvas dan buat ulang partikelnya.
        setupCanvas();
        createParticles();
      });
      
      this.particleResizeObserver.observe(canvas.parentElement);
    }
  },
  async mounted() {
    try {
      const [pageRes, featureRes, promoRes, teamRes] = await Promise.all([
        axios.get(PAGE_API_URL),
        axios.get(FEATURE_API_URL),
        axios.get(PROMO_API_URL),
        axios.get(TEAM_API_URL)
      ]);

      this.pageContent = pageRes.data.data || pageRes.data;
      this.features = featureRes.data.data || featureRes.data;
      this.promoContent = promoRes.data.data || promoRes.data;
      this.teamMembers = teamRes.data.data || teamRes.data;

      if (this.promoContent?.content && !Array.isArray(this.promoContent.content.list_items)) {
          this.promoContent.content.list_items = [];
      }

    } catch (error) {
        console.error("Gagal memuat data landing:", error);
        if (error.response?.status === 404) {
            console.error("Salah satu URL API tidak ditemukan (404). Periksa URL:", error.config.url);
        }
    }

    // Panggil initParticles SETELAH data API diterima dan DOM di-update
    this.$nextTick(() => {
        this.initParticles();
    });
  },

  // === FUNGSI CLEANUP YANG DIPERBARUI & BERSIH ===
  beforeUnmount() {
    console.log("Unmounting, cleaning up particle observers and animation");
    
    // 1. Hentikan loop animasi
    if (this.particleAnimationFrameId) {
      cancelAnimationFrame(this.particleAnimationFrameId);
    }
    
    // 2. Hentikan pengamatan observer
    if (this.particleIntersectionObserver) {
      this.particleIntersectionObserver.disconnect();
    }
    
    // 3. Hentikan pengamatan resize observer
    if (this.particleResizeObserver) {
      this.particleResizeObserver.disconnect();
    }
  },
  components: {
    Navbar,
    FooterComponent,
  },
};
</script>