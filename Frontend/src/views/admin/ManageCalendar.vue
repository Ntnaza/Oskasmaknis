<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">

      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-blueGray-700 text-xl font-bold">
            {{ isEditing ? 'Edit Jadwal Kegiatan' : 'Tambah Jadwal Kegiatan Baru' }}
          </h6>
          <!-- Penanda Angkatan Aktif -->
          <small v-if="angkatanStore.activeAngkatan" class="text-emerald-600 font-bold block mt-1">
            Mengelola untuk: {{ angkatanStore.activeAngkatan.name }}
          </small>
        </div>
        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
          <form @submit.prevent="submitForm">
            
            <div class="relative w-full mb-3 mt-6">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                Nama Kegiatan
              </label>
              <input 
                type="text" 
                v-model="form.title" 
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="Cth: PORSENI 2025" 
                required 
              />
            </div>

            <div class="flex flex-wrap">
              <div class="w-full lg:w-4/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                    Kategori
                  </label>
                  <select v-model="form.category" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" required>
                    <option value="" disabled>-- Pilih Kategori --</option>
                    <option value="Event Sekolah">Event Sekolah</option>
                    <option value="Upacara">Upacara</option>
                    <option value="Lomba">Lomba</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>
              </div>
              <div class="w-full lg:w-4/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                    Tanggal Mulai
                  </label>
                  <input 
                    type="datetime-local" 
                    v-model="form.start_date" 
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                    required 
                  />
                </div>
              </div>
              <div class="w-full lg:w-4/12 px-4">
                <div class="relative w-full mb-3">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                    Tanggal Selesai (Opsional)
                  </label>
                  <input 
                    type="datetime-local" 
                    v-model="form.end_date"
                    :min="form.start_date"
                    class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                  />
                </div>
              </div>
            </div>

            <div class="relative w-full mb-3">
              <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">
                Deskripsi (Opsional)
              </label>
              <textarea 
                v-model="form.description" 
                rows="4" 
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="Info tambahan, cth: Petugas upacara, detail lomba, dll..."
              ></textarea>
            </div>

            <div class="flex mt-6">
              <button 
                class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" 
                type="submit"
                :disabled="isSubmitting"
              >
                <i v-if="isSubmitting" class="fas fa-spinner fa-spin mr-2"></i>
                {{ isEditing ? 'Simpan Perubahan' : 'Tambahkan Kegiatan' }}
              </button>
              <button 
                v-if="isEditing"
                @click="resetForm"
                class="bg-blueGray-500 text-white active:bg-blueGray-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" 
                type="button"
              >
                Batal Edit
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <h3 class="font-semibold text-lg text-blueGray-700">
            Daftar Kegiatan Terjadwal
          </h3>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Judul Kegiatan
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Kategori
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Mulai
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Selesai
                </th>
                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="isLoading">
                <td colspan="5" class="text-center p-4">Memuat data kegiatan...</td>
              </tr>
              <tr v-if="!isLoading && activities.length === 0">
                <td colspan="5" class="text-center p-4 text-blueGray-500">Belum ada kegiatan di angkatan ini.</td>
              </tr>
              <tr v-for="activity in activities" :key="activity.id">
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">
                  {{ activity.title }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ activity.category }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ formatDateTime(activity.start_date) }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  {{ activity.end_date ? formatDateTime(activity.end_date) : '-' }}
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <button @click="editActivity(activity)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md hover:bg-emerald-600" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button @click="deleteActivity(activity.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md hover:bg-red-600" title="Hapus">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAngkatanStore } from '@/stores/angkatan'; // IMPORT STORE

export default {
  data() {
    return {
      angkatanStore: useAngkatanStore(), // INISIALISASI STORE
      activities: [], 
      isLoading: true,
      isSubmitting: false,
      isEditing: false,
      form: this.getInitialForm(),
    };
  },
  // WATCHER: Refresh data saat angkatan berubah
  watch: {
    'angkatanStore.selectedId': {
      handler(newVal) {
        if (newVal) this.fetchActivities();
      },
      immediate: true
    }
  },
  methods: {
    getInitialForm() {
      return {
        id: null,
        title: '',
        category: '',
        start_date: '',
        end_date: '',
        description: '',
      };
    },
    formatDateTime(dateString) {
      if (!dateString) return '-';
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateString).toLocaleString('id-ID', options);
    },
    formatDateForInput(dateString) {
      if (!dateString) return null;
      try {
        const date = new Date(dateString);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        return date.toISOString().slice(0, 16);
      } catch (e) {
        console.error("Format tanggal salah:", dateString, e);
        return null;
      }
    },
    async fetchActivities() {
      if (!this.angkatanStore.selectedId) return;

      this.isLoading = true;
      try {
        // KIRIM PARAM ANGKATAN ID
        const response = await axios.get('/api/admin/calendar-activities', {
            params: { angkatan_id: this.angkatanStore.selectedId }
        });
        this.activities = response.data.data; 
      } catch (error) {
        console.error("Gagal mengambil kegiatan:", error);
      } finally {
        this.isLoading = false;
      }
    },
    async submitForm() {
      if (!this.angkatanStore.selectedId) {
          alert("Mohon pilih Angkatan terlebih dahulu!");
          return;
      }

      this.isSubmitting = true;
      try {
        // KIRIM DATA BERSAMA ANGKATAN ID
        const dataToSend = {
            ...this.form,
            angkatan_id: this.angkatanStore.selectedId
        };

        if (this.isEditing) {
          await axios.put(`/api/admin/calendar-activities/${this.form.id}`, dataToSend);
        } else {
          await axios.post('/api/admin/calendar-activities', dataToSend);
        }
        this.fetchActivities();
        this.resetForm();
      } catch (error) {
        console.error("Gagal menyimpan kegiatan:", error);
        alert("Gagal menyimpan. Pastikan semua data terisi benar.");
      } finally {
        this.isSubmitting = false;
      }
    },
    editActivity(activity) {
      this.isEditing = true;
      this.form = { 
        ...activity,
        start_date: this.formatDateForInput(activity.start_date),
        end_date: this.formatDateForInput(activity.end_date),
      };
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    resetForm() {
      this.isEditing = false;
      this.form = this.getInitialForm();
    },
    async deleteActivity(id) {
      if (!confirm("Apakah Anda yakin ingin menghapus jadwal ini?")) {
        return;
      }
      try {
        await axios.delete(`/api/admin/calendar-activities/${id}`);
        this.fetchActivities(); // Refresh
      } catch (error) {
        console.error("Gagal menghapus kegiatan:", error);
      }
    }
  },
};
</script>