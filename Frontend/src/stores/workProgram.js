import { defineStore } from 'pinia';
import api from '@/services/api';
import axios from 'axios';
import { useAngkatanStore } from './angkatan';

const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export const useWorkProgramStore = defineStore('workProgram', {
  state: () => ({
    workPrograms: [],
    workProgramsForSelection: [], // Untuk menyimpan daftar ID dan Judul
  }),
  actions: {
    async fetchWorkPrograms() {
      const angkatanStore = useAngkatanStore(); // <-- 2. Gunakan Store

      try {
        // <-- 3. Kirim parameter angkatan_id
        const response = await axios.get(`${API_BASE_URL}/api/work-programs`, {
            params: {
                angkatan_id: angkatanStore.selectedId
            }
        });
        this.workPrograms = response.data;
      } catch (error) {
        console.error("Gagal memuat proker:", error);
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