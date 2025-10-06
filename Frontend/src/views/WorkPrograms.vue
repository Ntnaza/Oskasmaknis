<template>
  <div>
    <navbar />
    <main>
      <div class="relative pt-16 pb-32 flex content-center items-center justify-center min-h-screen-75">
        <div
          class="absolute top-0 w-full h-full bg-center bg-cover"
          :style="{ backgroundImage: 'url(' + headerImageUrl + ')' }"
        >
          <span
            id="blackOverlay"
            class="w-full h-full absolute opacity-75 bg-black"
          ></span>
        </div>
        <div class="container relative mx-auto">
          <div class="items-center flex flex-wrap">
            <div class="w-full lg:w-8/12 px-4 ml-auto mr-auto text-center">
              <div>
                <h1 class="text-white font-semibold text-5xl">
                  Program Kerja Kami
                </h1>
                <p class="mt-4 text-lg text-blueGray-200">
                  Komitmen dan dedikasi kami untuk kemajuan sekolah tercermin dalam setiap program kerja yang kami rancang dan laksanakan.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <section class="relative py-20 bg-blueGray-200">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap -mt-48">
            <div v-for="program in workPrograms" :key="program.id" class="w-full md:w-6/12 lg:w-4/12 px-4">
              <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg">
                <div class="px-4 py-5 flex-auto">
                  <div class="flex justify-between items-start mb-4">
                    <h6 class="text-xl font-semibold">{{ program.title }}</h6>
                    <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full" :class="getStatusClass(program.status)">
                        {{ program.status }}
                    </span>
                  </div>
                  <p class="text-sm text-blueGray-500 mb-4">
                    <strong>Periode:</strong> {{ formatDate(program.start_date) }} - {{ formatDate(program.end_date) }}
                  </p>
                  <p class="mt-2 mb-4 text-blueGray-600">
                    {{ program.description }}
                  </p>
                </div>
              </div>
            </div>
            
            <div v-if="!workPrograms.length" class="w-full text-center py-12">
                <p class="text-blueGray-500">Memuat program kerja...</p>
            </div>

          </div>
        </div>
      </section>

    </main>
    <footer-component />
  </div>
</template>
<script>
import Navbar from "@/components/Navbars/AuthNavbar.vue";
import FooterComponent from "@/components/Footers/Footer.vue";
import axios from "axios";

export default {
  data() {
    return {
      workPrograms: [],
      headerImageUrl: "https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80",
    };
  },
  components: {
    Navbar,
    FooterComponent,
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return 'Akan Datang';
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('id-ID', options);
    },
    getStatusClass(status) {
      if (status === 'Selesai') return 'bg-emerald-200 text-emerald-800';
      if (status === 'Berjalan') return 'bg-orange-200 text-orange-800';
      return 'bg-blueGray-200 text-blueGray-800';
    },
    async fetchPrograms() {
      try {
        const response = await axios.get('http://localhost:8000/api/work-programs');
        this.workPrograms = response.data;
      } catch (error) {
        console.error("Gagal mengambil data program kerja:", error);
      }
    }
  },
  mounted() {
    this.fetchPrograms();
  },
};
</script>
