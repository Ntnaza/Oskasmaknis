<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded-lg bg-white border-0">

        <div class="rounded-t bg-white mb-0 px-6 py-6 border-b border-blueGray-200">
          <h6 class="text-blueGray-700 text-xl font-bold mb-4">
            Kelola Halaman Landing
          </h6>
          <ul class="flex flex-wrap text-sm font-medium text-center -mb-px" role="tablist">
            <li v-for="(tab, index) in landingTabs" :key="index" class="mr-2 last:mr-0" role="presentation">
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
                  ></span> </a>
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
  name: "KelolaLandingLayout",
  components: {},
  data() {
    return {
      landingTabs: [
        { label: "Hero", to: { name: "admin-kelola-landing-hero" } },
        { label: "Fitur", to: { name: "admin-kelola-landing-fitur" } },
        { label: "Promo", to: { name: "admin-kelola-landing-promo" } },
        { label: "Tim", to: { name: "admin-kelola-landing-tim" } },
      ],
    };
  },
};
</script>