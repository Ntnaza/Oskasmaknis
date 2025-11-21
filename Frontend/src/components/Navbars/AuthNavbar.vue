<template>
  <nav
    class="top-0 absolute z-50 w-full flex flex-wrap items-center justify-between px-2 py-3 navbar-expand-lg"
  >
    <div
      class="container px-4 mx-auto flex flex-wrap items-center justify-between"
    >
      <div
        class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start"
      >
        <router-link
          class="text-white text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase"
          to="/"
        >
          OSIS & MPK
        </router-link>
        <button
          class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
          type="button"
          v-on:click="setNavbarOpen"
        >
          <i class="text-white fas fa-bars"></i>
        </button>
      </div>
      <div
        class="lg:flex flex-grow items-center bg-white lg:bg-opacity-0 lg:shadow-none"
        :class="[navbarOpen ? 'block rounded shadow-lg' : 'hidden']"
        id="example-navbar-warning"
      >
        <ul class="flex flex-col lg:flex-row list-none lg:ml-auto">
          
          <li class="flex items-center">
            <router-link to="/program-kerja" v-slot="{ isActive }">
              <a
                class="lg:text-white text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold relative group"
                :class="[!isActive && 'lg:hover:text-blueGray-200']"
              >
                Program Kerja
                <span
                  class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 lg:bg-white bg-blueGray-700 transition-all duration-300" :class="[isActive ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
                ></span>
              </a>
            </router-link>
          </li>

          <li class="flex items-center">
            <router-link to="/berita-dan-galeri" v-slot="{ isActive }">
              <a
                class="lg:text-white text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold relative group"
                :class="[!isActive && 'lg:hover:text-blueGray-200']"
              >
                Berita & Galeri
                <span
                  class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 lg:bg-white bg-blueGray-700 transition-all duration-300" :class="[isActive ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
                ></span>
              </a>
            </router-link>
          </li>
          
          <li class="flex items-center">
            <router-link to="/kalender-kegiatan" v-slot="{ isActive }">
              <a
                class="lg:text-white text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold relative group"
                :class="[!isActive && 'lg:hover:text-blueGray-200']"
              >
                Kalender Kegiatan
                <span
                  class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 lg:bg-white bg-blueGray-700 transition-all duration-300" :class="[isActive ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
                ></span>
              </a>
            </router-link>
          </li>
          <li class="flex items-center">
            <router-link to="/landing" v-slot="{ isActive }">
              <a
                class="lg:text-white text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold relative group"
                :class="[!isActive && 'lg:hover:text-blueGray-200']"
              >
                Landing Page
                <span
                  class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 lg:bg-white bg-blueGray-700 transition-all duration-300" :class="[isActive ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
                ></span>
              </a>
            </router-link>
          </li>

          <li class="flex items-center">
            <router-link to="/auth/login" v-slot="{ isActive }">
              <a
                class="lg:text-white text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold relative group"
                :class="[!isActive && 'lg:hover:text-blueGray-200']"
              >
                Pengaturan
                <span
                  class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 lg:bg-white bg-blueGray-700 transition-all duration-300" :class="[isActive ? 'w-9/1R2' : 'w-0 group-hover:w-9/12']"
                ></span>
              </a>
            </router-link>
          </li>

          <li class="flex items-center">
            <a
              class="lg:text-white lg:hover:text-blueGray-200 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
              href="https://facebook.com/usernamekamu" target="_blank"
              title="Facebook"
            >
              <i
                class="lg:text-blueGray-200 text-blueGray-400 fab fa-facebook text-lg leading-lg"
              />
              <span class="lg:hidden inline-block ml-2">Facebook</span>
            </a>
          </li>

          <li class="flex items-center">
            <a
              class="lg:text-white lg:hover:text-blueGray-200 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
              href="https://twitter.com/usernamekamu" target="_blank"
              title="Twitter"
            >
              <i
                class="lg:text-blueGray-200 text-blueGray-400 fab fa-twitter text-lg leading-lg"
              />
              <span class="lg:hidden inline-block ml-2">Twitter</span>
            </a>
          </li>

          <li class="flex items-center">
            <a
              class="lg:text-white lg:hover:text-blueGray-200 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
              href="https://instagram.com/usernamekamu" target="_blank"
              title="Instagram"
            >
              <i
                class="lg:text-blueGray-200 text-blueGray-400 fab fa-instagram text-lg leading-lg"
              />
              <span class="lg:hidden inline-block ml-2">Instagram</span>
            </a>
          </li>

          <li class="flex items-center">
            <a
              class="lg:text-white lg:hover:text-blueGray-200 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
              href="https://tiktok.com/@usernamekamu" target="_blank"
              title="TikTok"
            >
              <i
                class="lg:text-blueGray-200 text-blueGray-400 fab fa-tiktok text-lg leading-lg"
              />
              <span class="lg:hidden inline-block ml-2">TikTok</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
// import PagesDropdown from "@/components/Dropdowns/PagesDropdown.vue"; // Dihapus
export default {
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
  components: {
    // PagesDropdown, // Dihapus
  },
};
</script>