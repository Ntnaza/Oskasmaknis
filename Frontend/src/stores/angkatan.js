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
      // (Cara paling aman untuk memastikan semua filter diterapkan)
      setTimeout(() => window.location.reload(), 100);
    },

    applyTheme() {
      const angkatan = this.activeAngkatan;
      if (!angkatan || !angkatan.theme_color) return;
      document.documentElement.style.setProperty('--theme-color', angkatan.theme_color);
    }
  }
});