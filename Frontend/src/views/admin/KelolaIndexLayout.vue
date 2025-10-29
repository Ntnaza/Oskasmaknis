<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded-lg bg-white border-0">

        <div class="rounded-t bg-white mb-0 px-6 py-6 border-b border-blueGray-200">
          <h6 class="text-blueGray-700 text-xl font-bold mb-4">
            Kelola Halaman Index Utama
          </h6>
          <ul class="flex flex-wrap text-sm font-medium text-center -mb-px" role="tablist">
            <li v-for="(tab, index) in indexTabs" :key="index" class="mr-2 last:mr-0" role="presentation">
              <router-link
                :to="tab.to"
                v-slot="{ href, navigate, isActive }"
                custom
              >
                <a
                  :href="href"
                  @click="navigate"
                  class="inline-block px-4 py-3 rounded-t-lg font-semibold uppercase relative group"
                  :class="[
                    isActive
                      ? 'text-emerald-600'
                      : 'text-blueGray-500 hover:text-blueGray-600',
                  ]"
                  role="tab"
                >
                  {{ tab.label }}
                  <span
                    class="absolute bottom-0 left-0 right-0 mx-auto h-0.5 bg-emerald-600 transition-all duration-300"
                    :class="[isActive ? 'w-9/12' : 'w-0 group-hover:w-9/12']"
                  ></span>
                </a>
              </router-link>
            </li>
          </ul>
        </div>

        <div class="flex-auto px-4 lg:px-10 py-10">
          <router-view />
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "KelolaIndexLayout",
  components: {},
  data() {
    return {
      // Definisikan data untuk tab-tab Kelola Index
      indexTabs: [
        { label: "Hero", to: { name: "admin-index-hero" } },
        { label: "Sambutan Ketua", to: { name: "admin-index-sambutan" } },
        { label: "Pembina", to: { name: "admin-index-pembina" } },
        { label: "Kolaborasi Jurusan", to: { name: "admin-index-jurusan" } },
        { label: "Sejarah OSKA", to: { name: "admin-index-sejarah" } },
        { label: "Ajakan Bergabung", to: { name: "admin-index-ajakan" } },
      ],
    };
  },
};
</script>