<template>
  <div class="print-page-container">
    
    <header class="print-controls">
      <h3 class="title">Cetak Kartu Anggota</h3>
      <div class="buttons">
        <button @click="goBack" class="btn-back">
          <i class="fas fa-arrow-left mr-2"></i> Kembali
        </button>
        <button @click="doPrint" class="btn-print">
          <i class="fas fa-print mr-2"></i> Cetak Halaman
        </button>
      </div>
    </header>

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
</template>

<script>
const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export default {
  name: "CardPrintPage",
  data() {
    return {
      teamMembers: [],
      customBackgroundUrl: null,
      defaultBackgroundColor: '#1E40AF',
      logoSekolah: '/assets/img/logo-sekolah.png',
      logoOska: '/assets/img/logo-oska.png',
    };
  },
  computed: {
    // SALIN KODE COMPUTED cardStyle DARI FILE SEBELUMNYA
    cardStyle() {
      if (this.customBackgroundUrl) {
        return {
          backgroundImage: `url(${this.customBackgroundUrl})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        };
      }
      return {
        backgroundColor: this.defaultBackgroundColor,
      };
    },
  },
  methods: {
    // SALIN SEMUA METHODS HELPER DARI FILE SEBELUMNYA
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

    // --- Methods Khusus Halaman Ini ---
    loadDataFromSession() {
      const data = sessionStorage.getItem('printData');
      const bg = sessionStorage.getItem('printBackground');
      
      if (data) {
        this.teamMembers = JSON.parse(data);
      }
      if (bg) {
        this.customBackgroundUrl = bg;
      }
    },
    goBack() {
      this.$router.go(-1); // Kembali ke halaman /admin/kartu-anggota
    },
    doPrint() {
      window.print(); // Ini adalah tombol cetak yang sesungguhnya
    }
  },
  mounted() {
    this.loadDataFromSession();
  }
};
</script>

<style scoped>
/*
 * ===============================================
 * STYLE UNTUK TAMPILAN HALAMAN CETAK (REFERENSI FOTO 2)
 * ===============================================
 */
.print-page-container {
  background-color: #f1f5f9; /* Background abu-abu seperti di referensi */
  min-height: 100vh;
}
.print-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  margin-bottom: 2rem;
}
.print-controls .title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #333;
}
.print-controls .buttons button {
  font-weight: bold;
  text-transform: uppercase;
  font-size: 0.875rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.25rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  cursor: pointer;
  transition: all 0.2s;
}
.print-controls .btn-back {
  background-color: #f1f5f9;
  color: #475569;
  margin-right: 1rem;
}
.print-controls .btn-back:hover {
  background-color: #e2e8f0;
}
.print-controls .btn-print {
  background-color: #10b981; /* Hijau (emerald) */
  color: white;
}
.print-controls .btn-print:hover {
  background-color: #059669;
}

/* Area print */
#print-area {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 1rem;
}

/*
 * ===============================================
 * SALIN SEMUA STYLE KARTU DARI FILE SEBELUMNYA KE SINI
 * ===============================================
 */

/* Grid untuk kartu-kartu */
.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
  padding-bottom: 2rem;
  /* (Kita gunakan 3 kolom untuk layar besar) */
  @media (min-width: 900px) {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Base style untuk 1 kartu (PORTRAIT) */
.member-card {
  width: 250px; 
  height: 400px;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  position: relative;
  color: white; 
  page-break-inside: avoid;
  break-inside: avoid;
  margin: 0 auto; /* Tengah di grid */
}

/* Overlay untuk teks (agar kontras) */
.card-content-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  padding: 1rem;
  background: linear-gradient(
    180deg, 
    rgba(0,0,0,0.5) 0%,   /* Gelap di atas untuk header */
    rgba(0,0,0,0) 30%,   /* Transparan di tengah */
    rgba(0,0,0,0.3) 60%,  /* Mulai gelap lagi... */
    rgba(0,0,0,0.9) 100%  /* Sangat gelap di bawah untuk teks & QR */
  );
}

/* Header (Logo Kiri, Teks, Logo Kanan) */
.card-header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between; 
  padding-bottom: 0.75rem;
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
}
.member-position {
  font-size: 0.875rem; 
  font-weight: 500;
  opacity: 0.9;
  text-shadow: 0 1px 2px rgba(0,0,0,0.5);
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

/*
 * ===============================================
 * CSS KHUSUS UNTUK PRINT (SUDAH DIPERBAIKI)
 * ===============================================
 */
@media print {
  /* Sembunyikan tombol dan header */
  .print-controls {
    display: none !important;
  }
  
  /* Hapus background halaman dan bayangan */
  .print-page-container {
    background-color: white !important;
    min-height: auto;
  }
  #print-area {
    max-width: 100%;
    margin: 0;
    padding: 0;
  }
  .member-card {
    box-shadow: none;
    border: 0.5px solid #ccc; /* Kasih garis tipis agar pas potong */
  }

  /* Atur layout grid untuk kertas A4 (mirip referensi foto 1) */
  .card-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr) !important; /* Paksa 3 kolom */
    gap: 1.5rem 1rem;
    padding: 0;
  }

  /*
   * ===============================================
   * PERBAIKAN BACKGROUND & WARNA CETAK
   * ===============================================
   */
  
  /* 1. Paksa browser untuk mencetak background */
  .member-card, .card-content-overlay {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  
  /* 2. Kembalikan gradient shadow yang benar (sama seperti di layar) */
  .card-content-overlay {
    padding: 0.5rem; /* 8px (dari kode Anda) */
    background: linear-gradient(
      180deg, 
      rgba(0,0,0,0.5) 0%,   /* Gelap di atas */
      rgba(0,0,0,0) 30%,   /* Transparan */
      rgba(0,0,0,0.3) 60%,  /* Mulai gelap */
      rgba(0,0,0,0.9) 100%  /* Sangat gelap di bawah */
    ) !important;
    /* color: black !important; (DIHAPUS, biarkan putih) */
  }
  
  /* 3. Hapus paksaan 'color: black' agar teks kembali putih */
  .member-name, .member-position {
    text-shadow: none !important; /* text-shadow tetap bagus dihilangkan */
    /* color: black; (DIHAPUS, biarkan putih) */
  }
  
  /*
   * ===============================================
   * ATURAN UKURAN (DARI KODE ANDA, SUDAH BENAR)
   * ===============================================
   */
   
  .member-card {
    /* 54mm x 86mm adalah ukuran KTP/Kartu Kredit */
    width: 54mm;
    height: 86mm;
  }
  
  /* Sesuaikan padding dan ukuran font di dalam kartu untuk print */
  .logo {
    width: 28px;
    height: 28px;
  }
  .card-title {
    font-size: 0.65rem; /* 8pt */
  }
  .card-body-inner {
    margin-top: 0.5rem; /* 8px */
  }
  .photo-container {
    width: 160px; /* Disesuaikan (dari kode Anda) */
    height: 200px; /* Disesuaikan (dari kode Anda) */
  }
  .member-details {
    bottom: 90px; /* Naikkan sedikit (dari kode Anda) */
  }
  .member-name {
    font-size: 1rem; /* 12pt (dari kode Anda) */
  }
  .member-position {
    font-size: 0.75rem; /* 9pt (dari kode Anda) */
  }
  .qr-code-container {
    bottom: 10px; /* Naikkan sedikit (dari kode Anda) */
  }
  .qr-code {
    width: 80px;
    height: 80px;
  }
}
</style>