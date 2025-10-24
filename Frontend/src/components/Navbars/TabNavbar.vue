<template>
  <div class="relative z-10 mb-6 border-b border-blueGray-200">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
      <li v-for="(tab, index) in tabs" :key="index" class="mr-2" role="presentation">
        <router-link
          :to="tab.to"
          v-slot="{ href, navigate, isActive }"
          custom
        >
          <a
            :href="href"
            @click="navigate"
            class="inline-block p-4 rounded-t-lg border-b-2 font-semibold uppercase"
            :class="[
              isActive
                ? 'text-emerald-600 border-emerald-600 active'
                : 'text-blueGray-500 hover:text-blueGray-600 hover:border-blueGray-300 border-transparent',
            ]"
            role="tab"
          >
            {{ tab.label }}
          </a>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "TabNavbar",
  props: {
    tabs: {
      type: Array,
      required: true,
      validator: (tabs) => {
        return tabs.every(tab => tab.label && tab.to);
      }
    }
  }
};
</script>