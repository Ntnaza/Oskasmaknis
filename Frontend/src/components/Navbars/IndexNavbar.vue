<template>
  <nav
    class="top-0 fixed z-50 w-full flex flex-wrap items-center justify-between px-2 py-3 navbar-expand-lg bg-white shadow"
  >
    <div
      class="container px-4 mx-auto flex flex-wrap items-center justify-between"
    >
      <div
        class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start"
      >
        <router-link to="/">
          <a
            class="text-blueGray-700 text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase"
            href="#pablo"
          >
            OSIS & MPK
          </a>
        </router-link>
        <button
          class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
          type="button"
          v-on:click="setNavbarOpen"
        >
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div
        class="lg:flex flex-grow items-center"
        :class="[navbarOpen ? 'block' : 'hidden']"
        id="example-navbar-warning"
      >
        <ul class="flex flex-col lg:flex-row list-none lg:ml-auto items-center">
          
          <li class="flex items-center mr-4">
            <div class="relative" v-if="angkatanStore.list.length > 0">
              <select 
                :value="angkatanStore.selectedId"
                @change="e => angkatanStore.changeAngkatan(e.target.value)"
                class="border-blueGray-300 bg-white text-blueGray-700 text-xs font-bold uppercase px-3 py-2 rounded shadow focus:outline-none focus:ring ease-linear transition-all duration-150 cursor-pointer"
                style="min-width: 150px;"
              >
                <option v-for="angkatan in angkatanStore.list" :key="angkatan.id" :value="angkatan.id">
                  {{ angkatan.year_period }} - {{ angkatan.name }}
                </option>
              </select>
            </div>
          </li>
          <li class="flex items-center">
            <router-link
              to="/program-kerja"
              class="hover:text-emerald-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold"
            >
              Program Kerja
            </router-link>
          </li>

          <li class="flex items-center">
            <router-link
              to="/berita-dan-galeri"
              class="hover:text-emerald-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold"
            >
              Berita & Galeri
            </router-link>
          </li>
          
          <li class="flex items-center">
            <router-link
              to="/kalender-kegiatan"
              class="hover:text-emerald-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold"
            >
              Kalender
            </router-link>
          </li>
          
          <li class="flex items-center">
            <router-link
              to="/landing"
              class="hover:text-emerald-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold"
            >
              Landing Page
            </router-link>
          </li>

          <li class="flex items-center">
            <router-link
              to="/auth/login"
              class="hover:text-emerald-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold"
            >
              Pengaturan
            </router-link>
          </li>

          <li class="flex items-center">
            <a class="hover:text-blueGray-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold" href="https://facebook.com/usernamekamu" target="_blank"><i class="text-blueGray-400 fab fa-facebook text-lg leading-lg" /><span class="lg:hidden inline-block ml-2">Facebook</span></a>
          </li>
          <li class="flex items-center">
            <a class="hover:text-blueGray-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold" href="https://twitter.com/usernamekamu" target="_blank"><i class="text-blueGray-400 fab fa-twitter text-lg leading-lg" /><span class="lg:hidden inline-block ml-2">Twitter</span></a>
          </li>
          <li class="flex items-center">
            <a class="hover:text-blueGray-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold" href="https://instagram.com/usernamekamu" target="_blank"><i class="text-blueGray-400 fab fa-instagram text-lg leading-lg" /><span class="lg:hidden inline-block ml-2">Instagram</span></a>
          </li>
          <li class="flex items-center">
            <a class="hover:text-blueGray-500 text-blueGray-700 px-3 py-2 flex items-center text-xs uppercase font-bold" href="https://tiktok.com/@usernamekamu" target="_blank"><i class="text-blueGray-400 fab fa-tiktok text-lg leading-lg" /><span class="lg:hidden inline-block ml-2">TikTok</span></a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
// Import Store
import { useAngkatanStore } from "@/stores/angkatan";
import { onMounted } from "vue";

export default {
  setup() {
    const angkatanStore = useAngkatanStore();

    onMounted(() => {
      // Ambil data angkatan saat navbar dimuat
      angkatanStore.fetchAngkatans();
    });

    return {
      angkatanStore,
    };
  },
  data() {
    return {
      navbarOpen: false,
    };
  },
  methods: {
    setNavbarOpen: function () {
      this.navbarOpen = !this.navbarOpen;
    },
  },
  components: {},
};
</script>