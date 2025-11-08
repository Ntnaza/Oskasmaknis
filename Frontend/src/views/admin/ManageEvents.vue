<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
      <div
        class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded"
        :class="[color === 'light' ? 'bg-white' : 'bg-emerald-900 text-white']"
      >
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <div class="flex flex-wrap items-center">
            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
              <h3
                class="font-semibold text-lg"
                :class="[color === 'light' ? 'text-blueGray-700' : 'text-white']"
              >
                Manajemen Event Absensi
              </h3>
            </div>
            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
              <button
                class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                type="button"
                @click="openCreateModal"
              >
                Tambah Event Baru
              </button>
            </div>
          </div>
        </div>

        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th
                  class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  :class="[
                    color === 'light'
                      ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                      : 'bg-emerald-800 text-emerald-300 border-emerald-700',
                  ]"
                >
                  Nama Event
                </th>
                <th
                  class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  :class="[
                    color === 'light'
                      ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                      : 'bg-emerald-800 text-emerald-300 border-emerald-700',
                  ]"
                >
                  Tanggal
                </th>
                <th
                  class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  :class="[
                    color === 'light'
                      ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                      : 'bg-emerald-800 text-emerald-300 border-emerald-700',
                  ]"
                >
                  Status
                </th>
                <th
                  class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                  :class="[
                    color === 'light'
                      ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                      : 'bg-emerald-800 text-emerald-300 border-emerald-700',
                  ]"
                >
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="event in events" :key="event.id">
                <th
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  {{ event.title }}
                </th>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  {{ formatDateTime(event.event_date) }}
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                >
                  <span
                    class="px-2 py-1 rounded-full text-xs font-bold"
                    :class="{
                      'bg-yellow-200 text-yellow-800': event.status === 'pending',
                      'bg-green-200 text-green-800': event.status === 'active',
                      'bg-red-200 text-red-800': event.status === 'finished',
                    }"
                  >
                    {{ event.status }}
                  </span>
                </td>
                <td
                  class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                >
                  <router-link
                    :to="{ name: 'admin-event-detail', params: { id: event.id } }"
                    class="text-emerald-500 border border-emerald-500 hover:bg-emerald-500 hover:text-white active:bg-emerald-600 text-xs font-bold px-3 py-1 rounded outline-none focus:outline-none mr-2"
                  >
                    Detail & QR
                  </router-link>
                  
                  <button
                    @click="deleteEvent(event.id, event.title)"
                    class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 text-xs font-bold px-3 py-1 rounded outline-none focus:outline-none"
                  >
                    Hapus
                  </button>
                  </td>
              </tr>
              <tr v-if="!events.length">
                <td colspan="4" class="text-center p-4">
                  Belum ada event yang dibuat.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div
      v-if="showCreateModal"
      class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto"
    >
      <div
        class="fixed inset-0 bg-black opacity-50"
        @click="closeCreateModal"
      ></div>
      <div
        class="relative w-auto max-w-lg mx-auto my-6"
        style="min-width: 500px"
      >
        <div
          class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg"
        >
          <div
            class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200"
          >
            <h3 class="text-2xl font-semibold">Buat Event Baru</h3>
            <button
              class="p-1 ml-auto bg-transparent border-0 text-black float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
              @click="closeCreateModal"
            >
              <span class="text-black h-6 w-6 text-2xl block">×</span>
            </button>
          </div>
          
          <div class="relative p-6 flex-auto">
            <form @submit.prevent="handleCreateEvent" id="createEventForm">
              <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-blueGray-700"
                  >Nama Event</label
                >
                <input
                  type="text"
                  id="title"
                  v-model="newEventForm.title"
                  class="mt-1 block w-full px-3 py-2 bg-white border border-blueGray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                  required
                />
              </div>
              <div class="mb-4">
                <label for="event_date" class="block text-sm font-medium text-blueGray-700"
                  >Tanggal & Waktu Event</label
                >
                <input
                  type="datetime-local"
                  id="event_date"
                  v-model="newEventForm.event_date"
                  class="mt-1 block w-full px-3 py-2 bg-white border border-blueGray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                  required
                />
              </div>
              <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-blueGray-700"
                  >Deskripsi (Opsional)</label
                >
                <textarea
                  id="description"
                  v-model="newEventForm.description"
                  rows="3"
                  class="mt-1 block w-full px-3 py-2 bg-white border border-blueGray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                ></textarea>
              </div>
            </form>
          </div>

          <div
            class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200"
          >
            <button
              class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button"
              @click="closeCreateModal"
            >
              Batal
            </button>
            <button
              class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="submit"
              form="createEventForm"
            >
              Simpan Event
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'; 

export default {
  data() {
    return {
      events: [], 
      color: 'light', 
      showCreateModal: false, 
      newEventForm: {
        title: '',
        description: '',
        event_date: '',
      },
    };
  },
  created() {
    this.fetchEvents();
  },
  methods: {
    // ... (fetchEvents, formatDateTime, openCreateModal, closeCreateModal, handleCreateEvent tidak berubah) ...
    async fetchEvents() {
      try {
        const response = await axios.get('/api/admin/events');
        this.events = response.data;
      } catch (error) {
        console.error("Gagal mengambil data events:", error);
      }
    },
    formatDateTime(dateTimeString) {
      if (!dateTimeString) return '-';
      const options = {
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
      };
      return new Date(dateTimeString).toLocaleString('id-ID', options);
    },
    openCreateModal() {
      this.newEventForm = {
        title: '',
        description: '',
        event_date: '',
      };
      this.showCreateModal = true;
    },
    closeCreateModal() {
      this.showCreateModal = false;
    },
    async handleCreateEvent() {
      try {
        await axios.post('/api/admin/events', this.newEventForm);
        this.closeCreateModal(); 
        this.fetchEvents();       
      } catch (error) {
        console.error("Gagal membuat event:", error);
      }
    },

    // ... (method deleteEvent tidak berubah) ...
    async deleteEvent(id, title) {
      // Tampilkan konfirmasi
      if (!confirm(`Apakah Anda yakin ingin menghapus event "${title}"?`)) {
        return; // Batal jika user klik 'Cancel'
      }

      try {
        // Panggil API 'destroy' yang sudah kita buat
        await axios.delete(`/api/admin/events/${id}`);
        
        // (Opsional) Tampilkan notifikasi sukses
        // alert('Event berhasil dihapus!');
        
        // Refresh data di tabel dengan memfilter data yang ada
        this.events = this.events.filter(event => event.id !== id);

      } catch (error) {
        console.error("Gagal menghapus event:", error);
        // (Opsional) Tampilkan notifikasi error
        // alert('Gagal menghapus event.');
      }
    }
  }
}
</script>