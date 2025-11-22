import { defineStore } from 'pinia';
import axios from 'axios';

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export const useAngkatanStore = defineStore('angkatan', {
  state: () => ({
    list: [],
    selectedId: localStorage.getItem('selected_angkatan_id') || null,
    loading: false,
  }),

  getters: {
    // Getter ini otomatis akan berisi 'card_background_url' dari backend
    activeAngkatan: (state) => state.list.find(a => a.id == state.selectedId),
  },

  actions: {
    async fetchAngkatans() {
      this.loading = true;
      try {
        const response = await axios.get(`${API_BASE_URL}/api/angkatans`);
        this.list = response.data;

        // Logika inisialisasi: Pilih yang aktif jika belum ada pilihan
        if (!this.selectedId || !this.list.find(a => a.id == this.selectedId)) {
          const activeOne = this.list.find(a => a.is_active);
          if (activeOne) {
            this.changeAngkatan(activeOne.id);
          }
        } else {
          this.applyTheme();
        }
      } catch (error) {
        console.error("Gagal ambil angkatan:", error);
      } finally {
        this.loading = false;
      }
    },

    changeAngkatan(id) {
      this.selectedId = id;
      localStorage.setItem('selected_angkatan_id', id);
      this.applyTheme();
      
      // Kita reload halaman agar semua komponen mengambil data baru
      setTimeout(() => window.location.reload(), 100);
    },

    applyTheme() {
      const angkatan = this.activeAngkatan;
      if (!angkatan || !angkatan.theme_color) return;

      // 1. Set Warna Utama
      document.documentElement.style.setProperty('--theme-color', angkatan.theme_color);
      
      // 2. Set Warna Gelap (untuk Hover)
      // Kita gunakan helper function di bawah untuk menggelapkan warna secara otomatis
      const darkColor = this.darkenColor(angkatan.theme_color, 20);
      document.documentElement.style.setProperty('--theme-color-dark', darkColor);
    },

    // Helper: Membuat warna Hex lebih gelap (untuk efek hover tombol)
    darkenColor(col, amt) {
      var usePound = false;
      if (col[0] == "#") {
        col = col.slice(1);
        usePound = true;
      }
      var num = parseInt(col, 16);
      var r = (num >> 16) + amt * -1;
      if (r > 255) r = 255;
      else if (r < 0) r = 0;
      var b = ((num >> 8) & 0x00FF) + amt * -1;
      if (b > 255) b = 255;
      else if (b < 0) b = 0;
      var g = (num & 0x0000FF) + amt * -1;
      if (g > 255) g = 255;
      else if (g < 0) g = 0;
      return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
    }
  }
});