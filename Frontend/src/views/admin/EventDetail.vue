<template>
  <div>
    <div class="px-4 pt-4">
      <router-link
        to="/admin/manage-events"
        class="text-emerald-500 hover:text-emerald-700"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar Event
      </router-link>
    </div>
    <div class="flex flex-wrap mt-4">
      <div class="w-full lg:w-5/12 px-4">
        <div
          class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white"
        >
          <div class="rounded-t mb-0 px-4 py-3 border-0">
            <h3 class="font-semibold text-lg text-blueGray-700">
              Pindai QR Code Anggota
            </h3>
          </div>
          <div class="p-6">
            <div v-if="loading" class="text-center">
              <i class="fas fa-spinner fa-spin text-2xl"></i>
              <p>Memuat data event...</p>
            </div>

            <div v-if="!loading && event.id">
              
              <div 
                class="border-4 border-dashed border-blueGray-200 rounded-lg p-2 mb-4 mx-auto" 
                style="width: 400px; height: 400px;"
              >
                
                <qrcode-stream 
                  v-if="event.status === 'active'"
                  @decode="onDecode" 
                  @error="onError" 
                  :track="paintBoundingBox"
                >
                  <div v-if="scanLoading" class="loading-indicator">
                    <i class="fas fa-spinner fa-spin text-4xl text-white"></i>
                  </div>
                </qrcode-stream>
                
                <div 
                  v-if="event.status !== 'active'"
                  class="text-center p-8 bg-yellow-100 rounded h-full flex flex-col justify-center items-center"
                >
                  <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                  <h4 class="text-xl font-bold mb-2">Absen Ditutup</h4>
                  <p class="text-blueGray-600 text-sm">
                    Klik 'Buka Absen' di bawah untuk memulai scanner.
                  </p>
                </div>
              </div>

              <div v-if="scanError" class="p-3 text-center bg-red-200 text-red-800 rounded mb-2">
                <strong>Error:</strong> {{ scanError }}
              </div>
              <div v-if="scanSuccess" class="p-3 text-center bg-green-200 text-green-800 rounded mb-2">
                <strong>Sukses:</strong> {{ scanSuccess }}
              </div>

              <hr class="my-4" />
              <div>
                <h4 class="font-semibold mb-2">Status Absensi</h4>
                <div class="flex space-x-2">
                  <button
                    @click="updateStatus('pending')"
                    :class="[
                      'font-bold uppercase text-xs px-4 py-2 rounded shadow outline-none focus:outline-none ease-linear transition-all duration-150',
                      event.status === 'pending'
                        ? 'bg-yellow-500 text-white'
                        : 'bg-blueGray-200 text-blueGray-700 hover:bg-yellow-300',
                    ]"
                  >
                    Pending
                  </button>
                  <button
                    @click="updateStatus('active')"
                    :class="[
                      'font-bold uppercase text-xs px-4 py-2 rounded shadow outline-none focus:outline-none ease-linear transition-all duration-150',
                      event.status === 'active'
                        ? 'bg-emerald-500 text-white'
                        : 'bg-blueGray-200 text-blueGray-700 hover:bg-emerald-300',
                    ]"
                  >
                    Buka Absen
                  </button>
                  <button
                    @click="updateStatus('finished')"
                    :class="[
                      'font-bold uppercase text-xs px-4 py-2 rounded shadow outline-none focus:outline-none ease-linear transition-all duration-150',
                      event.status === 'finished'
                        ? 'bg-red-500 text-white'
                        : 'bg-blueGray-200 text-blueGray-700 hover:bg-red-300',
                    ]"
                  >
                    Tutup Absen
                  </button>
                </div>
                <p class="text-xs text-blueGray-500 mt-2">
                  <i class="fas fa-info-circle mr-1"></i>
                  Hanya status <strong>'Buka Absen' (active)</strong> yang akan memunculkan scanner.
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="w-full lg:w-7/12 px-4">
        
        <div
          class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white"
        >
          <div class="rounded-t mb-0 px-4 py-3 border-0">
            <h3 class="font-semibold text-lg text-blueGray-700">
              Daftar Hadir ({{ event.attendances ? event.attendances.length : 0 }}
              Orang)
            </h3>
          </div>
          <div class="block w-full overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
            <table class="items-center w-full bg-transparent border-collapse">
              <thead>
                <tr>
                  <th
                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                  >
                    Nama Anggota
                  </th>
                  <th
                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                  >
                    Waktu Absen
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="loading">
                  <td colspan="2" class="text-center p-4">Memuat data...</td>
                </tr>
                <tr v-if="!loading && (!event.attendances || !event.attendances.length)">
                  <td colspan="2" class="text-center p-4">
                    Belum ada yang hadir.
                  </td>
                </tr>
                <tr
                  v-for="attendance in sortedAttendances"
                  :key="attendance.id"
                >
                  <td
                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                  >
                    {{ attendance.team_member ? attendance.team_member.name : "Anggota Dihapus" }}
                  </td>
                  <td
                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                  >
                    {{ formatTime(attendance.attended_at) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        <div
          class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white"
        >
          <div class="rounded-t mb-0 px-4 py-3 border-0">
            <h3 class="font-semibold text-lg text-blueGray-700">
              Belum Hadir ({{ absentMembers.length }} Orang)
            </h3>
          </div>
          <div class="block w-full overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
            <table class="items-center w-full bg-transparent border-collapse">
              <thead>
                <tr>
                  <th
                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                  >
                    Nama Anggota
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="loading">
                  <td class="text-center p-4">Memuat data...</td>
                </tr>
                <tr v-if="!loading && !absentMembers.length">
                  <td class="text-center p-4">
                    Semua anggota sudah hadir.
                  </td>
                </tr>
                <tr
                  v-for="member in absentMembers"
                  :key="member.id"
                >
                  <td
                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                  >
                    {{ member.name }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { QrcodeStream } from "vue-qrcode-reader";

export default {
  components: {
    QrcodeStream,
  },
  props: {
    id: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      event: {},
      allMembers: [],
      scanLoading: false,
      scanError: null,
      scanSuccess: null,
    };
  },
  computed: {
    sortedAttendances() {
      if (!this.event.attendances) {
        return [];
      }
      return [...this.event.attendances].sort((a, b) => {
        return new Date(b.attended_at) - new Date(a.attended_at);
      });
    },
    absentMembers() {
      if (!this.event.attendances || !this.allMembers.length) {
        return [];
      }
      const presentMemberIds = this.event.attendances.map(
        (attendance) => attendance.team_member_id
      );
      return this.allMembers.filter(
        (member) => !presentMemberIds.includes(member.id)
      );
    },
  },
  created() {
    this.fetchEventDetail();
  },
  methods: {
    async fetchEventDetail() {
      this.loading = true;
      try {
        const response = await axios.get(`/api/admin/events/${this.id}`);
        this.event = response.data.event;
        this.allMembers = response.data.all_members;
      } catch (error) {
        console.error("Gagal mengambil detail event:", error);
      } finally {
        this.loading = false;
      }
    },
    async updateStatus(newStatus) {
      if (this.event.status === newStatus) {
        return;
      }
      try {
        const response = await axios.put(`/api/admin/events/${this.id}`, {
          status: newStatus,
        });
        this.event.status = response.data.status;
      } catch (error) {
        console.error("Gagal mengubah status:", error);
      }
    },
    
    // --- INI VERSI DEBUG onDECODE ---
    async onDecode(decodedString) {
      // 1. TAMPILKAN DATA MENTAH DARI QR CODE!
      console.log('--- DATA QR TERBACA ---');
      console.log(decodedString);
      console.log('-------------------------');
      
      // 2. Hentikan loading agar bisa scan berkali-kali jika perlu
      this.scanLoading = false; 

      /*
      // Logika asli masih kita matikan sementara
      if (this.scanLoading) return;
      this.scanLoading = true;
      // ...dst
      */
    },
    // --- BATAS VERSI DEBUG ---

    onError(error) {
      if (error.name === 'NotAllowedError') {
        this.scanError = 'Izin kamera tidak diberikan.';
      } else if (error.name === 'NotFoundError') {
        this.scanError = 'Tidak ada kamera yang ditemukan.';
      } else {
        this.scanError = 'Error kamera: ' + error.message;
      }
    },
    paintBoundingBox(detectedCodes, ctx) {
      for (const detectedCode of detectedCodes) {
        const { boundingBox } = detectedCode;
        ctx.lineWidth = 4;
        ctx.strokeStyle = '#00FF00';
        ctx.strokeRect(boundingBox.x, boundingBox.y, boundingBox.width, boundingBox.height);
      }
    },
    markAttendance(qrData) {
      return axios.post(`/api/admin/events/${this.id}/attend`, {
        qr_data: qrData
      });
    },
    formatDateTime(dateTimeString) {
      if (!dateTimeString) return "-";
      const options = {
        year: "numeric", month: "long", day: "numeric",
        hour: "2-digit", minute: "2-digit",
      };
      return new Date(dateTimeString).toLocaleString("id-ID", options);
    },
    formatTime(dateTimeString) {
      if (!dateTimeString) return "-";
      const options = {
        hour: "2-digit", minute: "2-digit", second: "2-digit",
      };
      return new Date(dateTimeString).toLocaleTimeString("id-ID", options);
    },
  },
};
</script>

<style scoped>
.loading-indicator {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}

/* Fix kamera mirror */
:deep(video) {
  transform: scaleX(-1) !important;
}
</style>