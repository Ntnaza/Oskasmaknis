<template>
  <div>
    <navbar />
    <main class="profile-page">
      <section class="relative block h-500-px">
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          :style="{ backgroundImage: 'url(' + headerImageUrl + ')' }"
        >
          <span
            id="blackOverlay"
            class="w-full h-full absolute opacity-50 bg-black"
          ></span>
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
      </section>
      <section class="relative py-16 bg-blueGray-200">
        <div class="container mx-auto px-4">
          <div
            class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64"
          >
            <div class="px-6" v-if="profileData">
              <div class="flex flex-wrap justify-center">
                <div
                  class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center"
                >
                  <div class="relative">
                    <img
                      alt="..."
                      :src="profileImageUrl"
                      class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px"
                    />
                  </div>
                </div>
                <div
                  class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center"
                >
                  <!-- Bisa diisi tombol aksi jika perlu -->
                </div>
                <div class="w-full lg:w-4/12 px-4 lg:order-1">
                  <!-- Bisa diisi statistik jika perlu -->
                </div>
              </div>
              <!-- PERBAIKAN: Menambah margin top dari mt-16 menjadi mt-24 -->
              <div class="text-center mt-24">
                <h3
                  class="text-4xl font-semibold leading-normal mb-2 text-blueGray-700"
                >
                  {{ profileData.name }}
                </h3>
                <div
                  class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase"
                >
                  <i
                    class="fas fa-map-marker-alt mr-2 text-lg text-blueGray-400"
                  ></i>
                  {{ profileData.location }}
                </div>
              </div>
              <div class="mt-10 py-10 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <h4 class="text-xl font-semibold mb-4 text-blueGray-700 text-center">
                      Tentang Organisasi
                    </h4>
                    <p class="mb-4 text-lg leading-relaxed text-blueGray-700 text-left whitespace-pre-line">
                      {{ profileData.about }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
             <div v-else class="text-center p-8">
                <p class="text-blueGray-500">Memuat data profil...</p>
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
      profileData: null,
    };
  },
  computed: {
    headerImageUrl() {
      if (this.profileData && this.profileData.header_image_path) {
        const path = this.profileData.header_image_path;
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
      }
      return 'https://placehold.co/1920x500/E2E8F0/A0AEC0?text=Upload+Gambar+Latar';
    },
    profileImageUrl() {
      if (this.profileData && this.profileData.profile_image_path) {
        const path = this.profileData.profile_image_path;
        if (path.startsWith('http')) return path;
        return `http://localhost:8000/storage/${path}`;
      }
      return 'https://placehold.co/150x150/E2E8F0/A0AEC0?text=Logo';
    }
  },
  components: {
    Navbar,
    FooterComponent,
  },
  mounted() {
    axios.get('http://localhost:8000/api/profile')
      .then(response => {
        this.profileData = response.data;
      })
      .catch(error => {
        console.error("Gagal mengambil data profil:", error);
      });
  },
};
</script>

