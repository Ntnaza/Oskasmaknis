<template>
  <footer class="relative bg-blueGray-200 pt-8 pb-6">
    <div
      class="bottom-auto top-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden -mt-20 h-20"
      style="transform: translateZ(0);"
    >
      <svg
        class="absolute bottom-0 overflow-hidden"
        xmlns="http://www.w3.org/2000/svg"
        preserveAspectRatio="none"
        version="1.1"
        viewBox="0 0 2560 100"
        x="0"
        y="0"
      >
        <polygon
          class="text-blueGray-200 fill-current"
          points="2560 0 2560 100 0 100"
        ></polygon>
      </svg>
    </div>
    <div class="container mx-auto px-4">
      <div class="flex flex-wrap text-center lg:text-left lg:items-start">
        <div class="w-full lg:w-6/12 px-4">
          <h4 class="text-3xl font-semibold text-blueGray-700">Tetap Terhubung!</h4>
          <h5 class="text-lg mt-0 mb-2 text-blueGray-600">
            Temukan kami di platform media sosial berikut.
          </h5>
          <div class="mt-6 lg:mb-0 mb-6">
            
            <a
              href="https://twitter.com/usernamekamu"
              target="_blank"
              class="bg-white text-lightBlue-400 shadow-lg font-normal h-10 w-10 inline-flex items-center justify-center rounded-full outline-none focus:outline-none mr-2"
            >
              <i class="fab fa-twitter"></i>
            </a>
            
            <a
              href="https://facebook.com/usernamekamu"
              target="_blank"
              class="bg-white text-lightBlue-600 shadow-lg font-normal h-10 w-10 inline-flex items-center justify-center rounded-full outline-none focus:outline-none mr-2"
            >
              <i class="fab fa-facebook-square"></i>
            </a>
            
            <a
              href="https://instagram.com/usernamekamu"
              target="_blank"
              class="bg-white text-pink-400 shadow-lg font-normal h-10 w-10 inline-flex items-center justify-center rounded-full outline-none focus:outline-none mr-2"
            >
              <i class="fab fa-instagram"></i>
            </a>
            
            <a
              href="https://tiktok.com/@usernamekamu"
              target="_blank"
              class="bg-white text-blueGray-800 shadow-lg font-normal h-10 w-10 inline-flex items-center justify-center rounded-full outline-none focus:outline-none mr-2"
            >
              <i class="fab fa-tiktok"></i>
            </a>
          </div>
        </div>
        <div class="w-full lg:w-6/12 px-4">
          <div class="flex flex-wrap items-top mb-6">
            <div class="w-full lg:w-4/12 px-4">
              <span
                class="block uppercase text-blueGray-500 text-sm font-semibold mb-2"
              >
                Navigasi
              </span>
              <ul class="list-unstyled">
                <li>
                  <a
                    class="text-blueGray-600 hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                    href="/program-kerja"
                  >
                    Program Kerja
                  </a>
                </li>
                <li>
                  <a
                    class="text-blueGray-600 hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                    href="/berita-dan-galeri"
                  >
                    Berita & Galeri
                  </a>
                </li>
                <li>
                  <a
                    class="text-blueGray-600 hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                    href="/landing"
                  >
                    Landing Page
                  </a>
                </li>
                <li>
                  <a
                    class="text-blueGray-600 hover:text-blueGray-800 font-semibold block pb-2 text-sm"
                    href="/auth/login"
                  >
                    Pengaturan
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <hr class="my-6 border-blueGray-300" />
      <div
        class="flex flex-wrap items-center md:justify-between justify-center"
      >
        <div class="w-full md:w-4/12 px-4 mx-auto text-center">
          <div class="text-sm text-blueGray-500 font-semibold py-1">
            Copyright © {{ date }} OSIS & MPK SMK NURUL ISLAM CIANJUR.
          </div>
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