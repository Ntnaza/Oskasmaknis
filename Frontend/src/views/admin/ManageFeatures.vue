<template>
  <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
     <h6 class="text-blueGray-700 text-xl font-bold mb-6 mt-6"> {{ isEditing ? 'Edit Fitur' : 'Tambah Fitur Baru' }}
     </h6>
     <form @submit.prevent="submitForm">
       <div class="flex flex-wrap mt-6">
         <div class="w-full lg:w-8/12 px-4">
           <div class="flex flex-wrap">
             <div class="w-full lg:w-6/12 pr-4">
               <div class="relative w-full mb-3">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Judul</label>
                 <input type="text" v-model="form.title" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" required/>
               </div>
             </div>
             <div class="w-full lg:w-6/12 pl-4">
               <div class="relative w-full mb-3">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Urutan</label>
                 <input type="number" v-model.number="form.order" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" required/>
               </div>
             </div>
             <div class="w-full">
               <div class="relative w-full mb-3">
                 <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2">Deskripsi (Max 150 Karakter)</label>
                 <textarea v-model="form.description" class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" rows="4" maxlength="150" required></textarea>
                 <span class="text-xs text-blueGray-500 float-right mt-1">{{ form.description ? form.description.length : 0 }} / 150</span>
               </div>
             </div>
           </div>
         </div>
         <div class="w-full lg:w-4/12 px-4">
             <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg text-center py-6 border">
                 <h4 class="text-blueGray-600 text-sm font-bold mb-4 uppercase">Live Preview</h4>
                 <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 mx-auto shadow-lg rounded-full"
                      :class="`bg-${form.color}-400`">
                   <i :class="[form.icon, 'text-xl']"></i>
                 </div>
                 <h6 class="text-xl font-semibold">{{ form.title || 'Judul Fitur' }}</h6>
                 <p class="mt-2 text-blueGray-500 px-4 text-sm">{{ form.description || 'Deskripsi singkat fitur akan muncul di sini.' }}</p>
             </div>
         </div>
       </div>

       <div class="w-full px-4 mb-4">
         <label class="block uppercase text-blueGray-600 text-xs font-bold mb-3">Pilih Ikon</label>
         <div class="flex flex-wrap gap-2">
           <button v-for="icon in availableIcons" :key="icon" @click.prevent="form.icon = icon" type="button"
                   class="w-12 h-12 rounded-full inline-flex items-center justify-center transition-all duration-150"
                   :class="form.icon === icon ? `bg-emerald-500 text-white shadow-lg` : `bg-blueGray-200 text-blueGray-700 hover:bg-blueGray-300`">
             <i :class="[icon, 'text-xl']"></i>
           </button>
         </div>
       </div>

       <div class="w-full px-4">
         <label class="block uppercase text-blueGray-600 text-xs font-bold mb-3">Pilih Warna</label>
         <div class="flex flex-wrap gap-2">
               <button v-for="color in availableColors" :key="color" @click.prevent="form.color = color" type="button"
                       class="w-10 h-10 rounded-md inline-flex items-center justify-center transition-all duration-150 ring-offset-2"
                       :class="[ `bg-${color}-400`, form.color === color ? `ring-2 ring-blueGray-700` : `` ]">
               </button>
         </div>
       </div>

       <div class="w-full px-4 mt-8">
         <button class="bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg" type="submit">
           <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Simpan Perubahan' : 'Tambah Fitur' }}
         </button>
         <button v-if="isEditing" @click.prevent="resetForm" class="bg-blueGray-500 text-white active:bg-blueGray-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg ml-2" type="button">
           <i class="fas fa-times mr-2"></i> Batal Edit
         </button>
       </div>
     </form>

     <hr class="my-8 border-b-1 border-blueGray-200" />

     <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-lg rounded mt-8">
       <div class="rounded-t mb-0 px-4 py-3 border-0">
         <h3 class="font-semibold text-lg text-blueGray-700">Daftar Fitur</h3>
       </div>
       <div class="block w-full overflow-x-auto">
         <table class="items-center w-full bg-transparent border-collapse">
           <thead>
             <tr>
               <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Ikon</th>
               <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Judul</th>
               <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Deskripsi</th>
               <th class="px-6 align-middle py-3 text-xs uppercase whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">Aksi</th>
             </tr>
           </thead>
           <tbody>
             <tr v-for="feature in features" :key="feature.id" class="hover:bg-blueGray-50">
               <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center">
                 <i :class="[feature.icon, 'text-xl', `text-${feature.color}-500`]"></i>
               </td>
               <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 font-bold">{{ feature.title }}</td>
               <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs p-4" style="max-width: 300px; white-space: normal;">{{ feature.description }}</td>
               <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                 <button @click="editFeature(feature)" class="bg-emerald-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center mr-2 shadow-md hover:shadow-lg transition-all duration-150">
                   <i class="fas fa-pencil-alt"></i>
                 </button>
                 <button @click="deleteFeature(feature.id)" class="bg-red-500 text-white p-2 rounded-full h-8 w-8 inline-flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-150">
                   <i class="fas fa-trash"></i>
                 </button>
               </td>
             </tr>
           </tbody>
         </table>
       </div>
     </div>
   </div>
</template>

<script>
import axios from 'axios';
const API_URL = 'http://localhost:8000/api/features';

export default {
  data() {
    return {
      features: [],
      form: {
        id: null,
        icon: 'fas fa-star',
        color: 'red',
        title: '',
        description: '',
        order: 0
      },
      isEditing: false,
      availableIcons: [
        'fas fa-award', 'fas fa-retweet', 'fas fa-fingerprint',
        'fas fa-user-friends', 'fas fa-calendar-alt', 'fas fa-bullhorn',
        'fas fa-cogs', 'fas fa-book-open', 'fas fa-lightbulb'
      ],
      availableColors: [
        'red', 'orange', 'amber', 'emerald', 'teal', 'lightBlue',
        'indigo', 'purple', 'pink'
      ],
    };
  },
  methods: {
    async fetchFeatures() {
      try {
        const response = await axios.get(API_URL);
        // Urutkan berdasarkan 'order' saat mengambil data
        this.features = response.data.sort((a, b) => a.order - b.order);
      } catch (error) {
        console.error("Gagal mengambil daftar fitur:", error);
      }
    },
    submitForm() {
      if (this.isEditing) {
        this.updateFeature();
      } else {
        this.createFeature();
      }
    },
    async createFeature() {
      try {
        await axios.post(API_URL, this.form);
        alert('Fitur baru berhasil ditambahkan!');
        this.resetForm();
        await this.fetchFeatures(); // Gunakan await agar fetch selesai sebelum lanjut
      } catch (error) {
        console.error("Gagal menambah fitur:", error);
        alert('Gagal menambah fitur.');
      }
    },
    editFeature(feature) {
      this.isEditing = true;
      this.form = { ...feature };
      // Scroll ke atas form
       this.$el.scrollIntoView({ behavior: 'smooth' });
    },
    async updateFeature() {
      try {
        await axios.put(`${API_URL}/${this.form.id}`, this.form);
        alert('Fitur berhasil diperbarui!');
        this.resetForm();
        await this.fetchFeatures(); // Gunakan await
      } catch (error) {
        console.error("Gagal memperbarui fitur:", error);
        alert('Gagal memperbarui fitur.');
      }
    },
    async deleteFeature(id) {
      if (confirm('Apakah Anda yakin ingin menghapus fitur ini?')) {
        try {
          await axios.delete(`${API_URL}/${id}`);
          alert('Fitur berhasil dihapus.');
          await this.fetchFeatures(); // Gunakan await
        } catch (error) {
          console.error("Gagal menghapus fitur:", error);
          alert('Gagal menghapus fitur.');
        }
      }
    },
    resetForm() {
      this.isEditing = false;
      this.form = {
        id: null,
        icon: 'fas fa-star',
        color: 'red',
        title: '',
        description: '',
        order: 0
      };
    }
  },
  mounted() {
    this.fetchFeatures();
  },
};
</script>