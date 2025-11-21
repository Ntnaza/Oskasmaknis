<template>
  <footer class="block py-4">
    <div class="container mx-auto px-4">
      <hr class="mb-4 border-b-1 border-blueGray-200" />
      <div
        class="flex flex-wrap items-center md:justify-between justify-center"
      >
        <div class="w-full md:w-6/12 px-4"> <div
            class="text-sm text-blueGray-500 font-semibold py-1 text-center md:text-left"
          >
            Copyright © {{ date }} OSIS & MPK SMK NURUL ISLAM CIANJUR.
          </div>
        </div>
        <div class="w-full md:w-6/12 px-4">
          <ul class="flex flex-wrap list-none md:justify-end justify-center">
            <li>
              <router-link
                to="/program-kerja"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Program Kerja
              </router-link>
            </li>
            <li>
              <router-link
                to="/berita-dan-galeri"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Berita & Galeri
              </router-link>
            </li>
            <li>
              <router-link
                to="/kalender-kegiatan"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Kalender
              </router-link>
            </li>
            <li>
              <router-link
                to="/aspirasi/lacak"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Lacak Aspirasi
              </router-link>
            </li>
            <li>
              <router-link
                to="/landing"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Landing Page
              </router-link>
            </li>
            <li>
              <router-link
                to="/auth/login"
                class="text-blueGray-600 hover:text-blueGray-800 text-sm font-semibold block py-1 px-3"
              >
                Pengaturan
              </router-link>
            </li>
          </ul>
          </div>
      </div>
    </div>
  </footer>
</template>
<script>
export default {
  data() {
    return {
      date: new Date().getFullYear(),
    };
  },
};
</script>