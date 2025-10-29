<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-white border-0">
        <div class="rounded-t bg-white mb-0 px-6 py-6">
          <h6 class="text-blueGray-700 text-xl font-bold">
            {{ isEditing ? 'Edit Program Kerja' : 'Tambah Program Kerja Baru' }}
          </h6>
        </div>
        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
          <form @submit.prevent="submitForm">
            <div class="flex flex-wrap mt-6">
              <div class="w-full lg:w-8/12 px-4">
                <div class="relative w-full mb-8">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul Program Kerja</label>
                  <input type="text" v-model="form.title" class="form-input-underline" required/>
                </div>
              </div>

              <div class="w-full lg:w-4/12 px-4">
                   <div class="relative w-full mb-8">
                     <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Penanggung Jawab</label>
                     <select v-model="form.team_member_id" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" required>
                         <option :value="null" disabled>-- Pilih Anggota --</option>
                         <option v-for="member in teamMembers" :key="member.id" :value="member.id">
                             {{ member.name }}
                         </option>
                     </select>
                   </div>
              </div>

              <div class="w-full px-4">
                <div class="relative w-full mb-8">
                  <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi</label>
                  <textarea v-model="form.description" class="form-textarea-underline" rows="4" required></textarea>
                </div>
              </div>

              <div class="w-full lg:w-4/12 px-4">
                   <div class="relative w-full mb-8">
                     <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Tanggal Mulai</label>
                     <input type="date" v-model="form.start_date" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                   </div>
              </div>
              <div class="w-full lg:w-4/12 px-4">
                   <div class="relative w-full mb-8">
                     <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Tanggal Selesai</label>
                     <input type="date" v-model="form.end_date" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full"/>
                   </div>
              </div>
              <div class="w-full lg:w-4/12 px-4">
                   <div class="relative w-full mb-8">
                     <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Status</label>
                     <select v-model="form.status" class="border-0 px-3 py-3 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full">
                         <option>Direncanakan</option>
                         <option>Berjalan</option>
                         <option>Selesai</option>
                     </select>
                   </div>
              </div>

            </div>

            <div class="w-full px-4 mt-4">
              <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Simpan Perubahan' : 'Tambah Program' }}
              </button>
              <button v-if="isEditing" @click.prevent="resetForm" class="bg-blueGray-500 text-white active:bg-blueGray-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg ml-2" type="button">
                <i class="fas fa-times mr-2"></i> Batal Edit
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-lg rounded mt-8">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
          <h3 class="font-semibold text-lg text-blueGray-700">Daftar Program Kerja</h3>
        </div>
        <div class="block w-full overflow-x-auto">
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Judul</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Status</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Periode</th>
                <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="program in workPrograms" :key="program.id" class="hover:bg-blueGray-50">
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">{{ program.title }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                    <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full"
                          :class="getStatusClass(program.status)">
                      {{ program.status }}
                    </span>
                </td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ formatDate(program.start_date) }} - {{ formatDate(program.end_date) }}</td>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <button @click="editProgram(program)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button @click="deleteProgram(program.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md">
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
const API_WORK_PROGRAMS_URL = 'http://localhost:8000/api/work-programs';
const API_TEAM_MEMBERS_URL = 'http://localhost:8000/api/team-members';

export default {
  data() { return { workPrograms: [], teamMembers: [], form: { id: null, title: '', description: '', status: 'Direncanakan', start_date: '', end_date: '', team_member_id: null, }, isEditing: false, }; },
  methods: {
    formatDate(dateString) { if (!dateString) return 'N/A'; const options = { year: 'numeric', month: 'long', day: 'numeric' }; return new Date(dateString).toLocaleDateString('id-ID', options); },
    getStatusClass(status) { if (status === 'Selesai') return 'bg-emerald-200 text-emerald-800'; if (status === 'Berjalan') return 'bg-orange-200 text-orange-800'; return 'bg-blueGray-200 text-blueGray-800'; },
    async fetchAllData() { try { const [programsRes, membersRes] = await Promise.all([ axios.get(API_WORK_PROGRAMS_URL), axios.get(API_TEAM_MEMBERS_URL) ]); this.workPrograms = programsRes.data; this.teamMembers = membersRes.data; } catch (error) { console.error("Gagal ambil data:", error); } },
    async submitForm() { try { if (this.isEditing) { await axios.put(`${API_WORK_PROGRAMS_URL}/${this.form.id}`, this.form); alert('Proker diupdate!'); } else { await axios.post(API_WORK_PROGRAMS_URL, this.form); alert('Proker ditambah!'); } this.resetForm(); this.fetchAllData(); } catch (error) { console.error("Gagal simpan:", error.response?.data); alert('Gagal simpan.'); } },
    editProgram(program) { this.isEditing = true; this.form = { ...program }; window.scrollTo(0, 0); },
    async deleteProgram(id) { if (confirm('Yakin hapus?')) { try { await axios.delete(`${API_WORK_PROGRAMS_URL}/${id}`); alert('Proker dihapus.'); this.fetchAllData(); } catch (error) { console.error("Gagal hapus:", error); alert('Gagal hapus.'); } } },
    resetForm() { this.isEditing = false; this.form = { id: null, title: '', description: '', status: 'Direncanakan', start_date: '', end_date: '', team_member_id: null, }; }
  },
  mounted() { this.fetchAllData(); },
};
</script>

<style scoped>
.form-input-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-input-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
.form-textarea-underline { display: block; width: 100%; font-size: 1rem; padding-left: 0.125rem; padding-right: 0.125rem; padding-top: 0.5rem; padding-bottom: 0.5rem; border-width: 0; border-bottom-width: 2px; border-color: #E2E8F0; background-color: transparent; transition: border-color .15s ease-in-out; }
.form-textarea-underline:focus { outline: none; box-shadow: none; border-color: #10B981; }
</style>