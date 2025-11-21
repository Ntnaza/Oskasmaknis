import { defineStore } from 'pinia';
import api from '@/services/api';
import { useAngkatanStore } from './angkatan'; // <-- 1. Import Store Angkatan

export const useArticleStore = defineStore('article', {
  state: () => ({
    latestArticles: [],
    allArticles: [],
  }),
  actions: {
    // Fetch 3 Artikel Terbaru (untuk Landing Page)
    async fetchLatestArticles() {
      const angkatanStore = useAngkatanStore(); // <-- 2. Gunakan Store

      try {
        // <-- 3. Kirim parameter angkatan_id
        const response = await api.get('/articles/latest', {
            params: { angkatan_id: angkatanStore.selectedId }
        });
        
        const backendUrl = 'http://localhost:8000';

        // Proses perbaikan URL Gambar
        const articlesWithFullUrl = response.data.data ? response.data.data.map(article => {
          if (article.image_url && !article.image_url.startsWith('http')) {
            article.image_url = `${backendUrl}${article.image_url}`;
          }
          return article;
        }) : []; // Handle jika response.data.data kosong

        this.latestArticles = articlesWithFullUrl;

      } catch (error) {
        console.error('Gagal mengambil artikel terbaru:', error);
        this.latestArticles = [];
      }
    },

    // Fetch Semua Artikel (untuk Halaman Berita)
    async fetchAllArticles() {
       const angkatanStore = useAngkatanStore();
       const backendUrl = 'http://localhost:8000';
       
       try {
           // Panggil endpoint all-articles dengan filter angkatan
           const response = await api.get('/all-articles', {
                params: { angkatan_id: angkatanStore.selectedId }
           });
           
           // Proses perbaikan URL Gambar (sama seperti di atas)
           // Cek apakah response berupa array langsung atau paginated object
           const rawData = Array.isArray(response.data) ? response.data : (response.data.data || []);

           const articlesWithFullUrl = rawData.map(article => {
                if (article.image_url && !article.image_url.startsWith('http')) {
                    article.image_url = `${backendUrl}${article.image_url}`;
                }
                return article;
            });

           this.allArticles = articlesWithFullUrl;
       } catch (error) {
           console.error('Gagal mengambil semua artikel:', error);
           this.allArticles = [];
       }
    }
  },
});