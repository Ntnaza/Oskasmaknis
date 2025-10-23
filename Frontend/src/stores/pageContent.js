// frontend/src/stores/pageContent.js

import { defineStore } from 'pinia';
import api from '@/services/api';

export const usePageContentStore = defineStore('pageContent', {
  state: () => ({
    content: {},
  }),
  actions: {
    async fetchContentBySlug(slug) {
      try {
        const response = await api.get(`/page-content/${slug}`);
        this.content[slug] = response.data.data;
      } catch (error) {
        console.error(`Error fetching content for slug ${slug}:`, error);
      }
    },
    async updatePageContent(data) {
      // API kita mungkin butuh slug, jadi kita ambil dari data
      // Misal data = { index: { title: '...' } }
      const slug = Object.keys(data)[0]; 
      const payload = data[slug];
      
      try {
        await api.put(`/page-content/${slug}`, payload);
      } catch (error) {
        console.error(`Error updating content for slug ${slug}:`, error);
        throw error; // Lemparkan error agar bisa ditangkap di komponen
      }
    },
  },
});