<template>
  <div>
    <navbar />
    <main v-if="member" class="profile-page">
      <!-- Bagian Header dengan Gambar Latar -->
      <section class="relative block h-500-px">
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          :style="{ backgroundImage: 'url(' + getFullImageUrl(member.photo_path) + ')' }"
        >
          <span class="w-full h-full absolute opacity-75 bg-black"></span>
        </div>
        <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px" style="transform: translateZ(0);">
          <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
            <polygon class="text-blueGray-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
          </svg>
        </div>
      </section>
      
      <!-- Konten Utama -->
      <section class="relative py-16 bg-blueGray-200">
        <div class="container mx-auto px-4">
          <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
            <div class="px-6">
              <!-- Info Profil Anggota -->
              <div class="flex flex-wrap justify-center">
                <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                  <div class="relative">
                    <img
                      alt="..."
                      :src="getFullImageUrl(member.photo_path)"
                      class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px"
                    />
                  </div>
                </div>
              </div>
              <div class="text-center mt-24">
                <h3 class="text-4xl font-semibold leading-normal mb-2 text-blueGray-700">
                  {{ member.name }}
                </h3>
                <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase">
                  <i class="fas fa-briefcase mr-2 text-lg text-blueGray-400"></i>
                  {{ member.position }}
                </div>
              </div>

              <!-- Daftar Program Kerja -->
              <div class="mt-10 py-10 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <h4 class="text-xl font-semibold mb-6 text-blueGray-700 text-center">
                      Program Kerja yang Dipegang
                    </h4>
                    <div v-if="member.work_programs && member.work_programs.length > 0">
                      <div v-for="program in member.work_programs" :key="program.id" class="mb-6 p-4 border rounded-lg">
                        <h5 class="text-lg font-bold text-blueGray-800">{{ program.title }}</h5>
                        <p class="text-sm text-blueGray-500 mb-2">{{ program.status }}</p>
                        <p class="text-blueGray-600">{{ program.description }}</p>
                      </div>
                    </div>
                    <div v-else>
                      <p class="text-center text-blueGray-500">Anggota ini belum memiliki program kerja.</p>
                    </div>
                  </div>
                </div>
              </div>
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
  name: "AnggotaDetail",
  components: {
    Navbar,
    FooterComponent,
  },
  data() {
    return {
      member: null, // Wadah untuk menyimpan data anggota
    };
  },
  methods: {
    getFullImageUrl(path) {
      if (!path) return 'https://placehold.co/150x150/E2E8F0/A0AEC0?text=Foto';
      if (path.startsWith('http')) return path;
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchMemberData() {
      // Ambil ID anggota dari URL
      const memberId = this.$route.params.id;
      try {
        const response = await axios.get(`http://localhost:8000/api/team-members/${memberId}`);
        this.member = response.data;
      } catch (error) {
        console.error("Gagal mengambil data anggota:", error);
        // Opsional: Arahkan ke halaman 404 jika anggota tidak ditemukan
      }
    }
  },
  created() {
    // Panggil fungsi untuk mengambil data saat komponen dibuat
    this.fetchMemberData();
  },
};
</script>
