import { defineStore } from 'pinia';
import api from '@/services/api';

export const useWorkProgramStore = defineStore('workProgram', {
  state: () => ({
    workPrograms: [],
    workProgramsForSelection: [], // Untuk menyimpan daftar ID dan Judul
  }),
  actions: {
    // FUNGSI UNTUK HALAMAN PUBLIK (Index.vue)
    async fetchWorkPrograms() {
      try {
        const response = await api.get('/work-programs');
        
        // PERBAIKAN INI SUDAH BENAR: API-nya kirim [ ... ]
        this.workPrograms = response.data;

      } catch (error) {
        console.error('Error fetching work programs:', error);
      }
    },
    
    // FUNGSI UNTUK DROPDOWN DI HALAMAN ADMIN
    async fetchWorkProgramsForSelection() {
      try {
        const response = await api.get('/work-programs-selection');

        // ======================================================
        // PERBAIKAN: KEMBALIKAN KE KODE ASLI (response.data.data)
        // Halaman admin Anda mengharapkan data yang terbungkus { data: [...] }
        // ======================================================
        this.workProgramsForSelection = response.data.data;

      } catch (error) {
        console.error('Error fetching work programs for selection:', error);
      }
    },
  },
});