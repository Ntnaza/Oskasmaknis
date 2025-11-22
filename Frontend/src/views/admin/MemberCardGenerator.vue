<template>
  <div class="flex flex-wrap">
    <div class="w-full px-4">
      
      <div class="print-hide w-full py-4 mb-4">
        <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded-lg bg-white p-6">
          <h6 class="text-blueGray-700 text-xl font-bold mb-4">
            Pengaturan Kartu
            <span v-if="angkatanStore.activeAngkatan" class="text-emerald-600 text-sm ml-2">
              ({{ angkatanStore.activeAngkatan.name }})
            </span>
          </h6>
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end">
            
            <div class="relative mb-4 sm:mb-0">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                Background Kartu (Opsional)
              </label>
              <input 
                type="file" 
                @change="handleBackgroundUpload" 
                class="block w-full text-sm text-blueGray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                accept="image/*"
              />
              <small class="text-blueGray-400">Rekomendasi rasio portrait (misal: 54mm x 86mm)</small>
            </div>
            
            <div class="flex ml-auto">
              <button
                v-if="cardBackgroundFile" 
                @click="saveBackground"
                :disabled="isUploading"
                class="bg-blue-500 text-white active:bg-blue-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-4"
              >
                <i class="fas fa-save mr-2"></i>
                {{ isUploading ? 'Menyimpan...' : 'Simpan Background' }}
              </button>

              <button 
                @click="goToPrintPage" 
                class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none"
              >
                <i class="fas fa-print mr-2"></i>
                Cetak Kartu
              </button>
            </div>
          </div>
        </div>
      </div>

      <div id="print-area">
        <div class="card-grid">
          
          <div v-for="member in teamMembers" :key="member.id" class="member-card" :style="cardStyle">
            <div class="card-content-overlay">
              
              <div class="card-header-inner">
                <img :src="logoOska" alt="Logo OSIS/MPK" class="logo" />
                <span class="card-title">KARTU ANGGOTA<br>OSIS & MPK</span>
                <img :src="logoSekolah" alt="Logo Sekolah" class="logo" />
              </div>
              
              <div class="card-body-inner">
                <div class="photo-container">
                  <img 
                    :src="getMemberPhotoUrl(member)" 
                    @error="onImageError"
                    class="member-photo"
                    crossorigin="anonymous"
                  />
                </div>
                
                <div class="member-details">
                  <p class="member-name">{{ member.name }}</p>
                  <p class="member-position">{{ member.position }}</p>
                </div>
                
                <div class="qr-code-container">
                  <img 
                    :src="getMemberQrCodeUrl(member)" 
                    alt="QR Code" 
                    class="qr-code"
                  />
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAngkatanStore } from '@/stores/angkatan'; 

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export default {
  data() {
    return {
      angkatanStore: useAngkatanStore(), 
      teamMembers: [],
      cardBackgroundFile: null,
      customBackgroundUrl: null, 
      defaultBackgroundColor: '#1E40AF', 
      logoSekolah: '/assets/img/logo-sekolah.png', 
      logoOska: '/assets/img/logo-oska.png',
      isUploading: false,
    };
  },
  computed: {
    cardStyle() {
      if (this.customBackgroundUrl) {
        return {
          backgroundImage: `url(${this.customBackgroundUrl})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        };
      }
      else if (this.angkatanStore.activeAngkatan && this.angkatanStore.activeAngkatan.card_background_url) {
        return {
            backgroundImage: `url(${API_BASE_URL}${this.angkatanStore.activeAngkatan.card_background_url})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        };
      }
      return {
        backgroundColor: this.defaultBackgroundColor,
      };
    },
  },
  watch: {
    'angkatanStore.selectedId': {
      handler(newVal) {
        if (newVal) {
            this.fetchTeamMembers();
            // Reset input file saat ganti angkatan
            this.cardBackgroundFile = null;
            this.customBackgroundUrl = null;
        }
      },
      immediate: true 
    }
  },
  methods: {
    async fetchTeamMembers() {
      if (!this.angkatanStore.selectedId) return; 

      try {
        const response = await axios.get(`${API_BASE_URL}/api/team-members`, {
            params: {
                angkatan_id: this.angkatanStore.selectedId
            }
        });
        this.teamMembers = response.data.sort((a, b) => a.order - b.order);
      } catch (error) {
        console.error("Gagal mengambil daftar anggota:", error);
      }
    },
    getMemberPhotoUrl(member) {
      if (!member || !member.photo_path) {
        return 'https://placehold.co/150x200/E2E8F0/A0AEC0?text=Foto'; 
      }
      return `${API_BASE_URL}/api/team-members/${member.id}/photo`;
    },
    getMemberQrCodeUrl(member) {
      if (!member) return null;
      return `${API_BASE_URL}/api/team-members/${member.id}/qrcode`;
    },
    onImageError(event) {
      event.target.src = 'https://placehold.co/150x200/E2E8F0/A0AEC0?text=Foto';
    },

    handleBackgroundUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.cardBackgroundFile = file; 
        this.customBackgroundUrl = URL.createObjectURL(file); 
      } else {
        this.cardBackgroundFile = null;
        this.customBackgroundUrl = null; // Kembali ke default/database
      }
    },
    
    async saveBackground() {
      if (!this.cardBackgroundFile || !this.angkatanStore.selectedId) return;
      
      this.isUploading = true;
      const formData = new FormData();
      formData.append('card_background_file', this.cardBackgroundFile);
      formData.append('_method', 'PUT'); 

      try {
        // --- PERBAIKAN DI SINI: Hapus 'const response =' ---
        await axios.post(
          `${API_BASE_URL}/api/angkatans/${this.angkatanStore.selectedId}`, 
          formData, 
          { headers: { 'Content-Type': 'multipart/form-data' } }
        );
        // ---------------------------------------------------
        
        // Update data di Store
        await this.angkatanStore.fetchAngkatans();
        
        this.cardBackgroundFile = null;
        this.customBackgroundUrl = null; 
        
        alert('Background angkatan berhasil disimpan!');
      } catch (error) {
        console.error("Gagal menyimpan background:", error);
        alert('Gagal menyimpan background. Cek console.');
      } finally {
        this.isUploading = false;
      }
    },
    
    goToPrintPage() {
      sessionStorage.setItem('printData', JSON.stringify(this.teamMembers));
      
      // Ambil background dari computed property
      let bgToPrint = '';
      if (this.cardStyle.backgroundImage) {
         // Bersihkan 'url("...")' menjadi URL bersih
         bgToPrint = this.cardStyle.backgroundImage.slice(4, -1).replace(/["']/g, "");
      }
      sessionStorage.setItem('printBackground', bgToPrint);
      this.$router.push('/admin/kartu-cetak');
    }
  },
  mounted() {
    // Logic ada di watch
  }
};
</script>

<style scoped>
/* Grid untuk kartu-kartu */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
  padding-bottom: 2rem;
}

/* Base style untuk 1 kartu (PORTRAIT) */
.member-card {
  width: 250px; /* Lebar 54mm */
  height: 400px; /* Tinggi 86mm */
  border-radius: 0.75rem; /* 12px */
  overflow: hidden;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  position: relative;
  color: white; 
  page-break-inside: avoid;
  break-inside: avoid;
}

/* Overlay untuk teks (agar kontras) */
.card-content-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  padding: 1rem; /* 16px */
  
  background: linear-gradient(
    180deg, 
    rgba(0,0,0,0.5) 0%,   
    rgba(0,0,0,0) 30%,    
    rgba(0,0,0,0.3) 60%,  
    rgba(0,0,0,0.9) 100%  
  );
}

/* Header (Logo Kiri, Teks, Logo Kanan) */
.card-header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between; 
  padding-bottom: 0.75rem; /* 12px */
}
.logo {
  width: 35px; 
  height: 35px;
  object-fit: contain;
}
.card-title {
  font-weight: 700;
  font-size: 0.8rem; 
  line-height: 1.2;
  text-transform: uppercase;
  text-align: center; 
}

/* Body (Foto, Info, QR) */
.card-body-inner {
  position: relative; 
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-grow: 1; 
  text-align: center;
  margin-top: 1rem;
}

.photo-container {
  width: 210px; 
  height: 250px; 
  border-radius: 0.375rem; 
  overflow: hidden;
}

.member-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.member-details {
  position: absolute;
  bottom: 125px; 
  left: 0;
  right: 0;
  width: 100%;
}
.member-name {
  font-weight: 700;
  font-size: 1.25rem; 
  text-shadow: 0 1px 3px rgba(0,0,0,0.7);
  line-height: 1.2;
}
.member-position {
  font-size: 0.875rem; 
  font-weight: 500;
  opacity: 0.9;
  text-shadow: 0 1px 2px rgba(0,0,0,0.5);
  line-height: 1.2;
}

.qr-code-container {
  position: absolute;
  bottom: 15px; 
  left: 50%;
  transform: translateX(-50%);
  line-height: 0;
  
  background-color: white; 
}
.qr-code {
  width: 100px; 
  height: 100px; 
  display: block;
}


/* CSS KHUSUS UNTUK PRINT */
@media print {
  .print-hide, #app > div > nav, #app > div > header { 
    display: none !important; 
    visibility: hidden !important;
  }
  
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  
  #print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    margin: 0;
    padding: 10mm; 
  }

  .card-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10mm 5mm; 
  }

  .member-card {
    width: 54mm; 
    height: 86mm; 
    box-shadow: none;
    border: 0.5px solid #ccc; 
  }

  .card-content-overlay {
    padding: 4mm;
  }
  .logo {
    width: 28px;
    height: 28px;
  }
  .card-title {
    font-size: 0.65rem; /* 8pt */
  }
  .card-body-inner {
    margin-top: 4mm;
  }
  .photo-container {
    width: 40mm;
    height: 50mm;
    border: none; 
  }
  .member-details {
    margin-top: 3mm;
  }
  .member-name {
    font-size: 0.9rem; /* 11pt */
  }
  .member-position {
    font-size: 0.7rem; /* 9pt */
  }
  .qr-code-container {
    margin-top: 3mm;
    padding: 2mm;
  }
  .qr-code {
    width: 20mm;
    height: 20mm;
  }
}
</style>