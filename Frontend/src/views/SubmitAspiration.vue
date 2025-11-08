<template>
  <div>
    <main>
      <section class="relative w-full h-full py-40 min-h-screen">
        <div
          class="absolute top-0 w-full h-full bg-blueGray-800 bg-no-repeat bg-full"
          :style="{
            /* --- PERBAIKAN DI SINI --- */
            /* Menggunakan path publik, tanpa 'require' */
            backgroundImage: 'url(\'/img/register_bg.png\')'
            /* --- BATAS PERBAIKAN --- */
          }"
        ></div>
        
        <div class="container mx-auto px-4 h-full">
          <div class="flex content-center items-center justify-center h-full">
            <div class="w-full lg:w-8/12 px-4">
              
              <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200 border-0">
                <div class="rounded-t mb-0 px-6 py-6">
                  <div class="text-center mb-3">
                    <h6 class="text-blueGray-500 text-xl font-bold">
                      Kotak Aspirasi Digital
                    </h6>
                    <p class="text-blueGray-400">Sampaikan saran, kritik, atau ide Anda secara langsung.</p>
                  </div>
                  <hr class="mt-6 border-b-1 border-blueGray-300" />
                </div>
                
                <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                  
                  <form @submit.prevent="submitAspiration">
                    <div class="flex flex-wrap">
                      <div class="w-full lg:w-6/12 pr-4">
                        <div class="relative w-full mb-3">
                          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="category">
                            Kategori
                          </label>
                          <select v-model="form.category" id="category" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" required>
                            <option value="" disabled>-- Pilih Kategori --</option>
                            <option value="Fasilitas">Fasilitas</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Event">Event / Kegiatan</option>
                            <option value="Kesiswaan">Kesiswaan</option>
                            <option value="Lainnya">Lainnya</option>
                          </select>
                        </div>
                      </div>
                      <div class="w-full lg:w-6/12">
                        <div class="relative w-full mb-3">
                          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="subject">
                            Judul / Perihal
                          </label>
                          <input type="text" v-model="form.subject" id="subject" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Cth: Toilet lantai 2..." required />
                        </div>
                      </div>
                    </div>

                    <div class="relative w-full mb-3">
                      <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="message">
                        Isi Aspirasi
                      </label>
                      <textarea v-model="form.message" id="message" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" rows="4" placeholder="Jelaskan aspirasi Anda secara detail..." required></textarea>
                    </div>

                    <div class="relative w-full mb-3">
                      <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="attachment">
                        Lampiran (Opsional)
                      </label>
                      <input type="file" @change="handleFileUpload" id="attachment" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" />
                      <small class="text-blueGray-500">Maks 5MB. Tipe: jpg, png, pdf, docx.</small>
                    </div>

                    <hr class="mt-6 border-b-1 border-blueGray-300" />

                    <div class="relative w-full mb-3 mt-3">
                       <input type="checkbox" v-model="isAnonim" id="anonim" class="form-checkbox border-0 rounded text-blueGray-700 ml-1 w-5 h-5 ease-linear transition-all duration-150" />
                      <label class="inline-flex items-center cursor-pointer ml-2 text-blueGray-600 font-bold" htmlFor="anonim">
                        Kirim sebagai Anonim (Tanpa Nama)
                      </label>
                    </div>
                    
                    <div v-if="!isAnonim" class="flex flex-wrap">
                      <div class="w-full lg:w-6/12 pr-4">
                        <div class="relative w-full mb-3">
                          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="name">
                            Nama Anda
                          </label>
                          <input type="text" v-model="form.name" id="name" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Nama Lengkap" :required="!isAnonim" />
                        </div>
                      </div>
                      <div class="w-full lg:w-6/12">
                        <div class="relative w-full mb-3">
                          <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" htmlFor="contact">
                            Kontak (Email/WA - Opsional)
                          </label>
                          <input type="text" v-model="form.contact" id="contact" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Kontak agar kami bisa hubungi" />
                        </div>
                      </div>
                    </div>

                    <div class="text-center mt-6">
                      <button class="bg-blueGray-800 text-white active:bg-blueGray-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150" type="submit" :disabled="isLoading">
                        <i v-if="isLoading" class="fas fa-spinner fa-spin mr-2"></i>
                        {{ isLoading ? 'Mengirim...' : 'Kirim Aspirasi' }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              
              <div v-if="successMessage" class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-emerald-500 border-0 text-white p-6">
                <h3 class="text-xl font-bold">Aspirasi Terkirim!</h3>
                <p class="mt-2">{{ successMessage }}</p>
                <div class="mt-4 p-4 bg-emerald-700 rounded text-center">
                  <span class="block text-sm uppercase">Nomor Tiket Pelacakan Anda:</span>
                  <span class="block text-3xl font-bold mt-1">{{ ticketId }}</span>
                </div>
                <router-link to="/aspirasi/lacak" class="mt-4 text-center text-white font-bold underline">
                  Lacak status tiket Anda di sini
                </router-link>
                <button @click="resetForm" class="mt-4 bg-white text-emerald-700 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg">
                  Kirim Aspirasi Lain
                </button>
              </div>

              <div v-if="errorMessage" class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-red-500 border-0 text-white p-6">
                <h3 class="text-xl font-bold">Oops, terjadi kesalahan</h3>
                <p class="mt-2">{{ errorMessage }}</p>
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
// (Asumsi Anda punya komponen Navbar & Footer, jika tidak, hapus saja)
// import Navbar from "@/components/Navbars/AuthNavbar.vue";
// import Footer from "@/components/Footers/FooterSmall.vue";

export default {
  // components: {
  //   "navbar-component": Navbar,
  //   "footer-component": Footer,
  // },
  data() {
    return {
      isAnonim: false,
      form: {
        subject: '',
        category: '',
        message: '',
        name: '',
        contact: '',
        attachment: null,
      },
      isLoading: false,
      successMessage: '',
      errorMessage: '',
      ticketId: '',
    };
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file && file.size > 5 * 1024 * 1024) { // 5MB limit
        alert("Ukuran file terlalu besar! Maksimal 5MB.");
        event.target.value = null; // Reset input file
        return;
      }
      this.form.attachment = file;
    },
    
    async submitAspiration() {
      this.isLoading = true;
      this.successMessage = '';
      this.errorMessage = '';

      // Kita WAJIB pakai FormData karena ada file upload
      const formData = new FormData();
      formData.append('subject', this.form.subject);
      formData.append('category', this.form.category);
      formData.append('message', this.form.message);

      if (!this.isAnonim) {
        formData.append('name', this.form.name);
        formData.append('contact', this.form.contact);
      }
      
      if (this.form.attachment) {
        formData.append('attachment', this.form.attachment);
      }

      try {
        // Panggil API 'store' yang sudah kita buat
        // (axios.defaults.baseURL sudah di-set di main.js)
        const response = await axios.post('/api/aspirations', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        // Tampilkan pesan sukses
        this.successMessage = response.data.message;
        this.ticketId = response.data.ticket_id;
        
        // Sembunyikan form
        // (Kita bisa lakukan ini dengan v-if, tapi untuk
        // sementara kita reset saja)
         
      } catch (error) {
        if (error.response && error.response.status === 422) {
          // Error Validasi
          this.errorMessage = "Gagal mengirim: Pastikan semua kolom yang wajib diisi sudah benar.";
          // (Bisa juga loop error.response.data.errors)
        } else {
          // Error Server
          this.errorMessage = "Terjadi kesalahan di server. Silakan coba lagi nanti.";
        }
        console.error("Gagal mengirim aspirasi:", error);
      } finally {
        this.isLoading = false;
      }
    },
    
    resetForm() {
      // Reset semua state ke awal
      this.isAnonim = false;
      this.form = {
        subject: '',
        category: '',
        message: '',
        name: '',
        contact: '',
        attachment: null,
      };
      this.successMessage = '';
      this.errorMessage = '';
      this.ticketId = '';
      // Reset input file
      const inputFile = document.getElementById('attachment');
      if (inputFile) inputFile.value = '';
    }
  }
};
</script>