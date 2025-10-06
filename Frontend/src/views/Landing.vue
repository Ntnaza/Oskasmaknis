<template>
  <div>
    <navbar />
    <main>
      <!-- ... Bagian Hero, Fitur, dan Promo tidak berubah ... -->
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
                <h1 class="text-white font-semibold text-5xl">
                  {{ pageContent.title }}
                </h1>
                <p class="mt-4 text-lg text-blueGray-200">
                  {{ pageContent.subtitle }}
                </p>
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
          </div>
        </div>
      </section>

      <section v-if="promoContent" class="relative py-20">
        <div class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20 h-20" style="transform: translateZ(0);">
          <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
            <polygon class="text-white fill-current" points="2560 0 2560 100 0 100"></polygon>
          </svg>
        </div>
        <div class="container mx-auto px-4">
          <div class="items-center flex flex-wrap">
            <div class="w-full md:w-4/12 ml-auto mr-auto px-4">
              <img alt="..." class="max-w-full rounded-lg shadow-lg" :src="promoImageUrl" />
            </div>
            <div class="w-full md:w-5/12 ml-auto mr-auto px-4">
              <div class="md:pr-12">
                <div class="text-emerald-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-emerald-300">
                  <i :class="promoContent.content.icon" class="text-xl"></i>
                </div>
                <h3 class="text-3xl font-semibold text-blueGray-800">{{ promoContent.content.title }}</h3>
                <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
                  {{ promoContent.content.description }}
                </p>
                <ul class="list-none mt-6">
                  <li v-for="(item, index) in promoContent.content.list_items" :key="index" class="py-2">
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
      
      <section class="pt-20 pb-48 relative bg-white">
        <canvas ref="particleCanvas" class="absolute top-0 left-0 w-full h-full z-0"></canvas>
        
        <div class="container mx-auto px-4 relative z-10">
          <div class="flex flex-wrap justify-center text-center mb-24">
            <div class="w-full lg:w-6/12 px-4">
              <h2 class="text-4xl font-semibold text-blueGray-700">Struktur Inti Organisasi</h2>
              <p class="text-lg leading-relaxed m-4 text-blueGray-500">
                Berikut adalah anggota inti dari OSIS & MPK periode saat ini yang berdedikasi untuk memajukan sekolah.
              </p>
            </div>
          </div>
          <div class="flex flex-wrap justify-center">
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
      pageContent: {},
      features: [],
      promoContent: null,
      teamMembers: [],
    };
  },
  computed: {
    heroImageUrl() {
      if (this.pageContent && this.pageContent.hero_image_url) {
        const path = this.pageContent.hero_image_url;
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
      }
      return '';
    },
    promoImageUrl() {
      if (this.promoContent && this.promoContent.content.image_url) {
        const path = this.promoContent.content.image_url;
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
      }
      return 'https://placehold.co/600x400/E2E8F0/A0AEC0?text=Upload+Gambar';
    },
    getFullImageUrl() {
      return (path) => {
        if (!path) return 'https://placehold.co/120x120/E2E8F0/A0AEC0?text=Foto';
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
      }
    }
  },
  methods: {
    getSocialColor(platform) {
        const colors = {
            twitter: 'bg-lightBlue-400',
            facebook: 'bg-lightBlue-600',
            instagram: 'bg-pink-500',
            dribbble: 'bg-pink-500',
            github: 'bg-blueGray-700',
            google: 'bg-red-600',
        };
        return colors[platform.toLowerCase()] || 'bg-blueGray-700';
    },
    initParticles() {
      const canvas = this.$refs.particleCanvas;
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      
      let scale = window.devicePixelRatio || 1;
      let animationFrameId;
      
      const setupCanvas = () => {
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
          this.x = Math.random() * (canvas.width / scale);
          this.y = Math.random() * (canvas.height / scale);
          
          // PERBAIKAN: Ukuran partikel diperkecil
          const baseSize = Math.random() * 8 + 4; // Ukuran antara 4px dan 12px
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

          if (this.x > (canvas.width / scale) + this.size || this.x < -this.size) this.speedX *= -1;
          if (this.y > (canvas.height / scale) + this.size || this.y < -this.size) this.speedY *= -1;
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

      function createParticles() {
        particles = [];
        for (let i = 0; i < particleCount; i++) {
          particles.push(new Particle());
        }
      }
      
      function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < particles.length; i++) {
          particles[i].update();
          particles[i].draw();
        }
        animationFrameId = requestAnimationFrame(animateParticles);
      }
      
      const observer = new IntersectionObserver((entries) => {
          if (entries[0].isIntersecting) {
              setupCanvas();
              createParticles();
              animateParticles();
          } else {
              cancelAnimationFrame(animationFrameId);
          }
      }, { threshold: 0.01 });

      observer.observe(canvas.parentElement);
      
      window.addEventListener('resize', () => {
          if (canvas.parentElement.offsetParent !== null) {
            setupCanvas();
            createParticles();
          }
      });
    }
  },
  components: {
    Navbar,
    FooterComponent,
  },
  mounted() {
    this.initParticles();

    axios.get("http://localhost:8000/api/page/landing")
      .then(response => { this.pageContent = response.data; });

    axios.get("http://localhost:8000/api/features")
      .then(response => { this.features = response.data; });
      
    axios.get("http://localhost:8000/api/content-block/promo-section")
      .then(response => { this.promoContent = response.data; });

    axios.get("http://localhost:8000/api/team-members")
        .then(response => { this.teamMembers = response.data });
  },
};
</script>

