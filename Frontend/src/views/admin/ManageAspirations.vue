<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
      
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <div class="flex flex-wrap items-center">
            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
              <h3 class="font-semibold text-lg text-blueGray-700">
                Daftar Aspirasi Masuk
              </h3>
            </div>
          </div>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Tiket
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Perihal
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Kategori
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Status
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Tanggal Masuk
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="6" class="text-center p-4">
                  <i class="fas fa-spinner fa-spin text-2xl"></i>
                  <p>Memuat data...</p>
                </td>
              </tr>
              <tr v-if="!isLoading && aspirations.length === 0">
                <td colspan="6" class="text-center p-4 text-blueGray-500">
                  Belum ada aspirasi yang masuk.
                </td>
              </tr>
              <tr v-for="item in aspirations" :key="item.id">
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">
                  {{ item.ticket_id }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ item.subject }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ item.category }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <span class="px-2 py-1 rounded-full text-xs font-bold text-white" :class="statusColor(item.status)">
                    {{ item.status }}
                  </span>
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ formatDate(item.created_at) }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <button @click="openDetailModal(item)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md hover:bg-emerald-600 transition-colors" title="Lihat Detail & Update">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button @click="deleteAspiration(item.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md hover:bg-red-600 transition-colors" title="Hapus">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-if="selectedAspiration" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto">
      <div class="fixed inset-0 bg-black opacity-50" @click="closeDetailModal"></div>
      <div class="relative w-auto max-w-3xl mx-auto my-6" style="min-width: 600px">
        <div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg">
          <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
            <h3 class="text-2xl font-semibold">Detail Aspirasi ({{ selectedAspiration.ticket_id }})</h3>
            <button class="p-1 ml-auto bg-transparent border-0 text-black float-right text-3xl leading-none font-semibold" @click="closeDetailModal">
              <span class="text-black h-6 w-6 text-2xl block">×</span>
            </button>
          </div>
          
          <div class="relative p-6 flex-auto">
            <div class="flex flex-wrap">
              <div class="w-full lg:w-1/2 pr-4">
                <h4 class="text-lg font-bold mb-2">Data Pengirim</h4>
                <p class="mb-2"><strong class="w-24 inline-block">Nama:</strong> {{ selectedAspiration.name || '(Anonim)' }}</p>
                <p class="mb-2"><strong class="w-24 inline-block">Kontak:</strong> {{ selectedAspiration.contact || '(Tidak ada)' }}</p>
                <p class="mb-2"><strong class="w-24 inline-block">Kategori:</strong> {{ selectedAspiration.category }}</p>
                <p class="mb-2"><strong class="w-24 inline-block">Perihal:</strong> {{ selectedAspiration.subject }}</p>
                
                <h4 class="text-lg font-bold mt-4 mb-2">Isi Pesan</h4>
                <textarea class="border-0 px-3 py-3 text-blueGray-600 bg-blueGray-100 rounded text-sm shadow w-full" rows="5" :value="selectedAspiration.message" readonly></textarea>
                
                <h4 class="text-lg font-bold mt-4 mb-2">Lampiran</h4>
                <a v-if="selectedAspiration.file_path" :href="getAttachmentUrl(selectedAspiration.file_path)" target="_blank" class="text-emerald-500 hover:text-emerald-700 font-bold">
                  <i class="fas fa-link mr-1"></i> Lihat Lampiran
                </a>
                <p v-else class="text-blueGray-500">(Tidak ada lampiran)</p>
              </div>
              
              <div class="w-full lg:w-1/2 lg:pl-4">
                <form @submit.prevent="updateAspiration" id="updateForm">
                  <h4 class="text-lg font-bold mb-2">Tindakan Admin</h4>
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="status">
                      Ubah Status
                    </label>
                    <select v-model="form.status" id="status" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full">
                      <option>Baru</option>
                      <option>Sudah Dibaca</option>
                      <option>Sedang Didiskusikan</option>
                      <option>Sudah Ditindaklanjuti</option>
                      <option>Selesai</option>
                    </select>
                  </div>
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="internal_notes">
                      Catatan Internal (Hanya dilihat Admin)
                    </label>
                    <textarea v-model="form.internal_notes" id="internal_notes" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" rows="10" placeholder="Tulis progres tindak lanjut di sini..."></textarea>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200">
            <button class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm" type="button" @click="closeDetailModal">
              Batal
            </button>
            <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit" form="updateForm" :disabled="isUpdating">
              <i v-if="isUpdating" class="fas fa-spinner fa-spin mr-2"></i>
              Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
const API_BASE_URL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';

export default {
  data() {
    return {
      aspirations: [],
      isLoading: true,
      isUpdating: false,
      selectedAspiration: null, // Untuk kontrol modal
      form: { // Form untuk update
        status: '',
        internal_notes: ''
      }
    };
  },
  methods: {
    // Ambil data dari API
    async fetchAspirations() {
      this.isLoading = true;
      try {
        const response = await axios.get('/api/admin/aspirations');
        this.aspirations = response.data.data; // .data karena ini paginated
        // Nanti kita bisa tambahkan logika pagination di sini
      } catch (error) {
        console.error("Gagal mengambil data aspirasi:", error);
      } finally {
        this.isLoading = false;
      }
    },
    
    // Buka Modal
    openDetailModal(aspiration) {
      this.selectedAspiration = aspiration;
      // Isi form dengan data yang ada
      this.form.status = aspiration.status;
      this.form.internal_notes = aspiration.internal_notes;
    },
    
    // Tutup Modal
    closeDetailModal() {
      this.selectedAspiration = null;
    },
    
    // Kirim Update (Status / Catatan)
    async updateAspiration() {
      if (!this.selectedAspiration) return;
      this.isUpdating = true;
      
      try {
        const response = await axios.put(`/api/admin/aspirations/${this.selectedAspiration.id}`, this.form);
        
        // Update data di tabel secara lokal (biar reaktif)
        const index = this.aspirations.findIndex(item => item.id === this.selectedAspiration.id);
        if (index !== -1) {
          this.aspirations[index] = response.data.data;
        }
        
        this.closeDetailModal();
        // alert('Status berhasil diperbarui!');
      } catch (error) {
        console.error("Gagal mengupdate aspirasi:", error);
        alert("Gagal mengupdate, silakan coba lagi.");
      } finally {
        this.isUpdating = false;
      }
    },
    
    // Hapus Aspirasi
    async deleteAspiration(id) {
      if (!confirm("Apakah Anda yakin ingin menghapus aspirasi ini? Ini tidak bisa dikembalikan.")) {
        return;
      }
      
      try {
        await axios.delete(`/api/admin/aspirations/${id}`);
        // Hapus dari list di frontend
        this.aspirations = this.aspirations.filter(item => item.id !== id);
      } catch (error) {
        console.error("Gagal menghapus aspirasi:", error);
        alert("Gagal menghapus, silakan coba lagi.");
      }
    },
    
    // Helper
    getAttachmentUrl(path) {
      return `${API_BASE_URL}/storage/${path}`;
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleDateString('id-ID', options);
    },
    statusColor(status) {
      switch (status) {
        case 'Baru': return 'bg-blueGray-500';
        case 'Sudah Dibaca': return 'bg-sky-500';
        case 'Sedang Didiskusikan': return 'bg-orange-500';
        case 'Sudah Ditindaklanjuti': return 'bg-emerald-500';
        case 'Selesai': return 'bg-emerald-600';
        default: return 'bg-blueGray-700';
      }
    }
  },
  mounted() {
    this.fetchAspirations();
  }
};
</script>