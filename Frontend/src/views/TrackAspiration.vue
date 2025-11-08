<template>
  <div>
    <main>
      <section class="relative w-full h-full py-40 min-h-screen">
        <div
          class="absolute top-0 w-full h-full bg-blueGray-800 bg-no-repeat bg-full"
          :style="{
            backgroundImage: 'url(\'/img/register_bg.png\')'
          }"
        ></div>
        
        <div class="container mx-auto px-4 h-full">
          <div class="flex content-center items-center justify-center h-full">
            <div class="w-full lg:w-6/12 px-4">
              
              <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200 border-0">
                <div class="rounded-t mb-0 px-6 py-6">
                  <div class="text-center mb-3">
                    <h6 class="text-blueGray-500 text-xl font-bold">
                      Lacak Aspirasi Anda
                    </h6>
                  </div>
                  <hr class="mt-6 border-b-1 border-blueGray-300" />
                </div>
                
                <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                  
                  <form v-if="!ticket_id" @submit.prevent="searchTicket">
                    <p class="text-blueGray-500 text-center mb-4">
                      Masukkan Nomor Tiket yang Anda dapatkan saat mengirim aspirasi.
                    </p>
                    <div class="relative w-full mb-3">
                      <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="ticketInput">
                        Nomor Tiket
                      </label>
                      <input 
                        type="text" 
                        v-model="ticketInput" 
                        id="ticketInput" 
                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                        placeholder="Cth: ASP-2025-ABCD" 
                        required 
                      />
                    </div>
                    <div class="text-center mt-6">
                      <button class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150" type="submit">
                        Lacak Status
                      </button>
                    </div>
                  </form>

                  <div v-else>
                    
                    <div v-if="isLoading" class="text-center text-blueGray-600">
                      <i class="fas fa-spinner fa-spin text-2xl"></i>
                      <p class="mt-2">Mencari tiket...</p>
                    </div>

                    <div v-if="errorMessage" class="text-center p-4 bg-red-200 text-red-800 rounded">
                      <p class="font-bold">Tiket Tidak Ditemukan</p>
                      <p>{{ errorMessage }}</p>
                      <router-link to="/aspirasi/lacak" class="block text-sm font-bold underline mt-2">Coba cari lagi</router-link>
                    </div>
                    
                    <div v-if="result" class="text-blueGray-700">
                      <div class="mb-4">
                        <label class="block text-xs font-bold uppercase">Nomor Tiket</label>
                        <p class="text-xl font-bold">{{ result.ticket_id }}</p>
                      </div>
                      <div class="mb-4">
                        <label class="block text-xs font-bold uppercase">Perihal</label>
                        <p class="text-lg">{{ result.subject }}</p>
                      </div>
                      <div class="mb-4">
                        <label class="block text-xs font-bold uppercase">Kategori</label>
                        <p class="text-lg">{{ result.category }}</p>
                      </div>
                      <div class="mb-4">
                        <label class="block text-xs font-bold uppercase">Dikirim</label>
                        <p class="text-lg">{{ result.submitted_at }}</p>
                      </div>
                      <div class="mb-4">
                        <label class="block text-xs font-bold uppercase">Status Saat Ini</label>
                        <span class="text-lg font-bold px-3 py-1 rounded-full text-white" :class="statusColor(result.status)">
                          {{ result.status }}
                        </span>
                      </div>

                      <hr class="my-4 border-b-1 border-blueGray-300" />
                      <router-link to="/aspirasi/lacak" class="text-sm font-bold text-blueGray-600 hover:text-blueGray-800">
                        <i class="fas fa-search mr-1"></i>
                        Lacak Tiket Lain
                      </router-link>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  // Terima 'ticket_id' dari URL (jika ada)
  props: {
    ticket_id: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      ticketInput: '', // Untuk form pencarian
      isLoading: false,
      result: null, // Untuk menyimpan hasil
      errorMessage: '',
    };
  },
  methods: {
    // Navigasi ke halaman hasil
    searchTicket() {
      if (!this.ticketInput) return;
      this.$router.push({ 
        name: 'track-aspirasi-detail', 
        params: { ticket_id: this.ticketInput.toUpperCase() } 
      });
    },
    
    // Ambil data dari API
    async fetchTicketStatus() {
      if (!this.ticket_id) return; // Jangan lakukan apa-apa jika tidak ada ID

      this.isLoading = true;
      this.result = null;
      this.errorMessage = '';

      try {
        const response = await axios.get(`/api/aspirations/track/${this.ticket_id}`);
        this.result = response.data;
      } catch (error) {
        if (error.response && error.response.status === 404) {
          this.errorMessage = `Tiket "${this.ticket_id}" tidak dapat ditemukan.`;
        } else {
          this.errorMessage = "Terjadi kesalahan saat mengambil data.";
        }
        console.error("Gagal melacak tiket:", error);
      } finally {
        this.isLoading = false;
      }
    },

    // Helper untuk memberi warna status
    statusColor(status) {
      switch (status) {
        case 'Baru':
          return 'bg-blueGray-500';
        case 'Sudah Dibaca':
          return 'bg-sky-500';
        case 'Sedang Didiskusikan':
          return 'bg-orange-500';
        case 'Sudah Ditindaklanjuti':
          return 'bg-emerald-500';
        case 'Selesai':
          return 'bg-emerald-600';
        default:
          return 'bg-blueGray-700';
      }
    }
  },
  // Dipanggil saat halaman dimuat
  mounted() {
    this.fetchTicketStatus();
  },
  // Dipanggil jika user mencari lagi (misal: dari /lacak/1 ke /lacak/2)
  watch: {
    ticket_id() {
      this.fetchTicketStatus();
    }
  }
};
</script>