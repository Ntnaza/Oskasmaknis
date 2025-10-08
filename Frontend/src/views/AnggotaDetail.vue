<template>
  <div>
    <!-- Tampilkan konten jika data 'member' sudah ada -->
    <div v-if="member">
      <navbar />
      <main class="profile-page">
        <!-- ======================================================= -->
        <!--      BAGIAN HEADER DENGAN GAMBAR LATAR (SUDAH OKE)      -->
        <!-- ======================================================= -->
        <section class="relative block h-500-px">
          <div
            class="absolute top-0 w-full h-full bg-center bg-cover"
            :style="{ backgroundImage: 'url(' + getFullImageUrl(member.header_photo_path, 'header') + ')' }"
          >
            <span class="w-full h-full absolute opacity-50 bg-black"></span>
          </div>
          <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px" style="transform: translateZ(0);">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
              <polygon class="text-slate-50 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
          </div>
        </section>

        <!-- ======================================================= -->
        <!--      KONTEN UTAMA DENGAN KARTU                          -->
        <!-- ======================================================= -->
        <section class="relative py-16 bg-slate-50">
          <div class="container mx-auto px-4">
            
            <!-- Tombol Kembali -->
            <div class="mb-6">
                <button @click="goBack" class="text-emerald-600 hover:text-emerald-800 font-semibold transition-colors duration-300 group">
                    <i class="fas fa-arrow-left mr-2 transition-transform duration-300 group-hover:-translate-x-1"></i>
                    Kembali ke Daftar Anggota
                </button>
            </div>

            <!-- Kartu Profil Melayang -->
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64 p-6 md:p-8">
              <div class="flex flex-col items-center">
                <!-- Foto Profil -->
                <div class="relative w-40 h-40 -mt-24">
                   <img
                    :src="getFullImageUrl(member.photo_path, 'profile')"
                    alt="..."
                    class="w-full h-full rounded-full object-cover"
                  />
                </div>

                <!-- Informasi Teks -->
                <div class="text-center mt-6">
                  <h1 class="text-4xl font-bold text-slate-800">
                    {{ member.name }}
                  </h1>
                  <p class="mt-1 text-lg font-semibold text-emerald-600">
                    {{ member.position }}
                  </p>
                </div>
              </div>

              <!-- ======================================================= -->
              <!--   [DISEMPURNAKAN] KONTEN DETAIL DI DALAM KARTU          -->
              <!-- ======================================================= -->
              <div class="mt-10 pt-10 border-t border-slate-200">
                <div class="max-w-4xl mx-auto">
                  
                  <!-- Sambutan (tanpa judul) -->
                  <div class="mb-12 text-center">
                    <p class="text-xl text-slate-600 italic">"{{ (member.bio_data && member.bio_data.sambutan) || 'Selamat datang di halaman profil saya.' }}"</p>
                  </div>

                  <div class="space-y-14">
                    <!-- Data Diri (Biodata + Sosial Media) -->
                    <div class="detail-section">
                      <h2 class="detail-title">Data Diri</h2>
                      <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-x-10">
                        <!-- Kolom Kiri -->
                        <div class="space-y-12">
                          <div class="biodata-item">
                            <span class="biodata-label">Jurusan</span>
                            <span class="biodata-value">{{ (member.bio_data && member.bio_data.jurusan) || '-' }}</span>
                          </div>
                          <div class="biodata-item">
                            <span class="biodata-label">Hobi</span>
                            <span class="biodata-value">{{ (member.bio_data && member.bio_data.hobi) || '-' }}</span>
                          </div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div class="space-y-12">
                          <div class="biodata-item">
                            <span class="biodata-label">TTL</span>
                            <span class="biodata-value">{{ (member.bio_data && member.bio_data.ttl) || '-' }}</span>
                          </div>
                          <div class="biodata-item">
                            <span class="biodata-label">Sosial Media</span>
                            <div class="flex items-center gap-4 mt-2">
                              <a v-for="(url, platform) in member.social_links" :key="platform" :href="url" target="_blank" rel="noopener noreferrer" class="social-link-detail">
                                <i :class="getSocialIcon(platform)"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Visi & Misi -->
                    <div class="detail-section">
                       <h2 class="detail-title">Visi & Misi</h2>
                       <div class="content-text">
                          <p class="whitespace-pre-line">{{ (member.bio_data && member.bio_data.visi_misi) || 'Belum ditambahkan.' }}</p>
                       </div>
                    </div>
                    <!-- Prestasi -->
                    <div class="detail-section">
                      <h2 class="detail-title">Prestasi & Pengalaman</h2>
                      <div class="content-text">
                        <ul v-if="member.bio_data && member.bio_data.prestasi && member.bio_data.prestasi.length > 0">
                          <li v-for="(item, index) in member.bio_data.prestasi" :key="index">{{ item }}</li>
                        </ul>
                        <p v-else>Belum ada yang ditambahkan.</p>
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

    <!-- Tampilan Loading -->
    <div v-else class="flex items-center justify-center h-screen bg-slate-100">
      <div class="text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-emerald-500"></i>
        <p class="mt-4 text-xl text-slate-500">Memuat data anggota...</p>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export default {
  name: "AnggotaDetail",
  components: {
    Navbar,
    FooterComponent,
  },
  data() {
    return {
      member: null,
    };
  },
  methods: {
    goBack() {
      if (window.history.length > 2) {
        this.$router.go(-1);
      } else {
        // Ganti 'DaftarTim' dengan nama route halaman tim Anda jika ada
        this.$router.push('/'); 
      }
    },
    getFullImageUrl(path, type = 'default') {
      const placeholder = type === 'header' 
        ? 'https://placehold.co/1920x500/E2E8F0/A0AEC0?text=Latar' 
        : 'https://placehold.co/400x400/E2E8F0/A0AEC0?text=Foto';
      if (!path) return placeholder;
      if (path.startsWith('http')) return path;
      return `${API_BASE_URL}/storage/${path}`;
    },
    getSocialIcon(platform) {
        const icons = {
            instagram: 'fab fa-instagram', facebook: 'fab fa-facebook-square', tiktok: 'fab fa-tiktok',
            twitter: 'fab fa-twitter', youtube: 'fab fa-youtube', linkedin: 'fab fa-linkedin',
            whatsapp: 'fab fa-whatsapp', telegram: 'fab fa-telegram-plane', email: 'fas fa-envelope'
        };
        return icons[platform] || 'fas fa-link';
    },
    async fetchMemberData() {
      const memberId = this.$route.params.id;
      try {
        const response = await axios.get(`${API_BASE_URL}/api/team-members/${memberId}`);
        if (!response.data.bio_data) response.data.bio_data = {};
        if (!response.data.social_links) response.data.social_links = {};
        this.member = response.data;
      } catch (error) {
        console.error("Gagal mengambil data anggota:", error);
        if (error.response && error.response.status === 404) {
          // this.$router.push('/404'); 
        }
      }
    }
  },
  created() {
    this.fetchMemberData();
  },
};
</script>

<style scoped>
/* Styling untuk section detail */
.detail-section:not(:last-child) {
  padding-bottom: 2.5rem;
}

/* Styling untuk Judul (garis bawah dihapus) */
.detail-title {
  font-size: 1.25rem; /* text-xl */
  font-weight: 700;
  color: #334155; /* slate-700 */
  margin-bottom: 1.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: inline-block;
}

/* Styling untuk Biodata Label */
.biodata-label {
  display: block;
  color: #334155; /* slate-700 */
  font-size: 1.125rem; /* text-lg */
  font-weight: 700;
  margin-bottom: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.biodata-value {
  display: block;
  color: #475569; /* slate-600 */
  font-size: 1rem; /* text-base */
  font-weight: 500;
}

/* Styling untuk ikon media sosial di bagian detail */
.social-link-detail {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3rem; /* 48px */
    height: 3rem; /* 48px */
    background-color: #f8fafc; /* slate-50 */
    border: 1px solid #e2e8f0; /* slate-200 */
    border-radius: 9999px;
    color: #475569; /* slate-600 */
    font-size: 1.25rem; /* text-xl */
    transition: all 0.3s ease;
}
.social-link-detail:hover {
    color: #10b981; /* emerald-500 */
    border-color: #10b981;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

/* Styling umum untuk area konten teks */
.content-text {
  color: #475569; /* slate-600 */
  font-size: 1rem;
  line-height: 1.75;
}
.content-text p {
  margin-bottom: 1.25em;
}
.content-text ul {
  list-style-position: outside;
  margin-left: 1.25em;
  list-style-type: disc;
}
.content-text li {
  margin-bottom: 0.75em;
}

/* Utility untuk menjaga format teks dari textarea */
.whitespace-pre-line {
    white-space: pre-line;
}
</style>

