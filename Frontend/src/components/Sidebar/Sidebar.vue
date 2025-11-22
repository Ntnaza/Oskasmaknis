<template>
  
  <nav
    class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6"
  >
  
    <div
      class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto"
    >
      <button
        class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
        type="button"
        v-on:click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
      >
        <i class="fas fa-bars"></i>
      </button>
      <router-link
        class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
        to="/"
      >
        OSIS & MPK
      </router-link>
      <ul class="md:hidden items-center flex flex-wrap list-none">
        <li class="inline-block relative">
          <notification-dropdown />
        </li>
        <li class="inline-block relative">
          <user-dropdown />
        </li>
      </ul>
      <div
        class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded"
        v-bind:class="collapseShow"
      >
        <div
          class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-200"
        >
          <div class="flex flex-wrap">
            <div class="w-6/12">
              <router-link
                class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                to="/"
              >
                OSIS & MPK </router-link>
            </div>
            <div class="w-6/12 flex justify-end">
              <button
                type="button"
                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                v-on:click="toggleCollapseShow('hidden')"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>
        <form class="mt-6 mb-4 md:hidden">
          <div class="mb-3 pt-0">
            <input
              type="text"
              placeholder="Search"
              class="border-0 px-3 py-2 h-12 border border-solid border-blueGray-500 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-base leading-snug shadow-none outline-none focus:outline-none w-full font-normal"
            />
          </div>
        </form>
<div class="mt-4 mb-4 px-4" v-if="angkatanStore.list.length > 0">
    <label class="block text-blueGray-500 text-xs font-bold mb-2 uppercase">Pilih Angkatan</label>
    <select 
    :value="angkatanStore.selectedId"
    @change="e => angkatanStore.changeAngkatan(e.target.value)"
    class="border-0 px-3 py-2 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full cursor-pointer"
    >
    <option v-for="angkatan in angkatanStore.list" :key="angkatan.id" :value="angkatan.id">
        {{ angkatan.year_period }}
    </option>
    </select>
</div>
        <hr class="my-4 md:min-w-full" />
        <h6
          class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
        >
          Admin Menu Utama
        </h6>
        <ul class="md:flex-col md:min-w-full flex flex-col list-none">
          <li class="items-center">
            <router-link
              to="/admin/dashboard"
              v-slot="{ href, navigate, isActive }"
            >
              <a
                :href="href"
                @click="navigate"
                class="text-xs uppercase py-3 font-bold block"
                :class="[
                  isActive
                    ? 'text-emerald-500 hover:text-emerald-600'
                    : 'text-blueGray-700 hover:text-blueGray-500',
                ]"
              >
                <i
                  class="fas fa-tv mr-2 text-sm"
                  :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"
                ></i>
                Dashboard
              </a>
            </router-link>
          </li>

          <li class="items-center">
            <router-link
              to="/admin/kelola-landing"
              v-slot="{ href, navigate }" >
              <a
                :href="href"
                @click="navigate"
                class="text-xs uppercase py-3 font-bold block"
                :class="[
                  $route.path.startsWith('/admin/kelola-landing')
                    ? 'text-emerald-500 hover:text-emerald-600'
                    : 'text-blueGray-700 hover:text-blueGray-500',
                ]"
              >
                <i
                  class="fas fa-file-alt mr-2 text-sm"
                  :class="[$route.path.startsWith('/admin/kelola-landing') ? 'opacity-75' : 'text-blueGray-300']"
                ></i>
                Kelola Landing
              </a>
            </router-link>
          </li>

          <li class="items-center">
            <router-link to="/admin/manage-profile" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-id-card mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Profil
              </a>
            </router-link>
          </li>
          
          <li class="items-center">
            <router-link to="/admin/kartu-anggota" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-address-card mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kartu Anggota
              </a>
            </router-link>
          </li>
          <li class="items-center">
            <router-link to="/admin/manage-work-programs" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-tasks mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Proker
              </a>
            </router-link>
          </li>
          
          <li class="items-center">
            <router-link to="/admin/manage-articles" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-newspaper mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Artikel
              </a>
            </router-link>
          </li>

          <li class="items-center">
            <router-link to="/admin/manage-events" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-calendar-check mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Event
              </a>
            </router-link>
          </li>
          
          <li class="items-center">
            <router-link to="/admin/manage-calendar" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-calendar-alt mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Kalender
              </a>
            </router-link>
          </li>
          <li class="items-center">
            <router-link to="/admin/manage-aspirations" v-slot="{ href, navigate, isActive }">
              <a :href="href" @click="navigate" class="text-xs uppercase py-3 font-bold block"
                 :class="[isActive ? 'text-emerald-500 hover:text-emerald-600' : 'text-blueGray-700 hover:text-blueGray-500']">
                <i class="fas fa-inbox mr-2 text-sm" :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"></i>
                Kelola Aspirasi
              </a>
            </router-link>
          </li>
          <li class="items-center">
            <router-link
              to="/admin/manage-index"
              v-slot="{ href, navigate, isActive }"
            >
              <a
                :href="href"
                @click="navigate"
                class="text-xs uppercase py-3 font-bold block"
                :class="[
                  isActive
                    ? 'text-emerald-500 hover:text-emerald-600'
                    : 'text-blueGray-700 hover:text-blueGray-500',
                ]"
              >
                <i
                  class="fas fa-home mr-2 text-sm"
                  :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"
                ></i>
                Kelola Index
              </a>
            </router-link>
          </li> 
          
          <li class="items-center">
            <router-link
              to="/admin/settings"
              v-slot="{ href, navigate, isActive }"
            >
              <a
                :href="href"
                @click="navigate"
                class="text-xs uppercase py-3 font-bold block"
                :class="[
                  isActive
                    ? 'text-emerald-500 hover:text-emerald-600'
                    : 'text-blueGray-700 hover:text-blueGray-500',
                ]"
              >
                <i
                  class="fas fa-tools mr-2 text-sm"
                  :class="[isActive ? 'opacity-75' : 'text-blueGray-300']"
                ></i>
                Settings
              </a>
            </router-link>
          </li>
        </ul>

        <hr class="my-4 md:min-w-full" />
        <h6
          class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
        >
          Auth Pages
        </h6>
        <ul class="md:flex-col md:min-w-full flex flex-col list-none md:mb-4">
          <li class="items-center">
            <router-link
              class="text-blueGray-700 hover:text-blueGray-500 text-xs uppercase py-3 font-bold block"
              to="/auth/login"
            >
              <i class="fas fa-fingerprint text-blueGray-300 mr-2 text-sm"></i>
              Login
            </router-link>
          </li>

          <li class="items-center">
            <router-link
              class="text-blueGray-700 hover:text-blueGray-500 text-xs uppercase py-3 font-bold block"
              to="/auth/register"
            >
              <i
                class="fas fa-clipboard-list text-blueGray-300 mr-2 text-sm"
              ></i>
              Register
            </router-link>
          </li>
        </ul>

      </div>
    </div>
  </nav>
</template>


<script>
import NotificationDropdown from "@/components/Dropdowns/NotificationDropdown.vue";
import UserDropdown from "@/components/Dropdowns/UserDropdown.vue";
import { useAngkatanStore } from "@/stores/angkatan";
import { onMounted } from "vue";

// Di dalam export default:

export default {
  setup() {
  const angkatanStore = useAngkatanStore();
  onMounted(() => angkatanStore.fetchAngkatans());
  return { angkatanStore };
},
  data() {
    return {
      collapseShow: "hidden",
    };
  },
  methods: {
    toggleCollapseShow: function (classes) {
      this.collapseShow = classes;
    },
  },
  components: {
    NotificationDropdown,
    UserDropdown,
  },
};
</script>