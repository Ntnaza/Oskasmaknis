import { defineStore } from 'pinia';
import api from '@/services/api';

export const useArticleStore = defineStore('article', {
  state: () => ({
    latestArticles: [],
    allArticles: [],
  }),
  actions: {
    async fetchLatestArticles() {
      try {
        const response = await api.get('/articles/latest');
        
        // ======================================================
        // PERUBAHAN UTAMA ADA DI SINI
        // Kita tuliskan alamat backend secara langsung
        const backendUrl = 'http://localhost:8000';
        // ======================================================

        // Proses setiap artikel untuk memperbaiki URL gambarnya
        const articlesWithFullUrl = response.data.data.map(article => {
          // Cek jika image_url ada dan BUKAN URL lengkap (tidak diawali 'http')
          if (article.image_url && !article.image_url.startsWith('http')) {
            // Gabungkan alamat backend dengan path gambar
            article.image_url = `${backendUrl}${article.image_url}`;
          }
          return article;
        });

        // Simpan data yang URL-nya sudah diperbaiki
        this.latestArticles = articlesWithFullUrl;

      } catch (error) {
        console.error('Gagal mengambil artikel terbaru:', error);
        this.latestArticles = [];
      }
    },
  },
});