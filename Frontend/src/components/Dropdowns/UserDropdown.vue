<template>
  <div>
    <a
      class="text-blueGray-500 block"
      href="#pablo"
      ref="btnDropdownRef"
      v-on:click="toggleDropdown($event)"
    >
      <div class="items-center flex">
        <span
          class="w-10 h-10 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full"
        >
          <i class="fas fa-user"></i>
        </span>
      </div>
    </a>

    <div
      ref="popoverDropdownRef"
      class="bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
      v-bind:class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
    >
      <router-link
        to="/admin/profile" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-blueGray-100"
        @click="closeDropdownOnly" >
        <i class="fas fa-user-circle mr-2"></i> Profil
      </router-link>

      <div class="h-0 my-2 border border-solid border-blueGray-100" />

      <a
        href="#"
        @click.prevent="logout"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-red-500 hover:text-white"
      >
        <i class="fas fa-sign-out-alt mr-2"></i> Logout
      </a>
    </div>
  </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
// import image from "@/assets/img/team-1-800x800.jpg"; // Aktifkan jika pakai gambar

export default {
  data() {
    return {
      dropdownPopoverShow: false,
      popperInstance: null, // Cukup simpan instance
      // image: image, // Aktifkan jika pakai gambar
    };
  },
  methods: {
    toggleDropdown: function (event) {
      event.preventDefault();
      if (this.dropdownPopoverShow) {
        // Jika sedang terbuka -> tutup
        this.dropdownPopoverShow = false;
        // Hancurkan Popper saat ditutup
        if (this.popperInstance) {
          this.popperInstance.destroy();
          this.popperInstance = null;
        }
      } else {
        // Jika sedang tertutup -> buka
        this.dropdownPopoverShow = true;
        // Buat Popper baru saat dibuka
        this.popperInstance = createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
          placement: "bottom-end", // Coba 'bottom-end' agar rata kanan
        });
      }
    },
    closeDropdownOnly() {
      // Fungsi untuk hanya menutup dropdown (tanpa logic lain)
      this.dropdownPopoverShow = false;
      if (this.popperInstance) {
        this.popperInstance.destroy();
        this.popperInstance = null;
      }
    },
    logout() {
      // Tambahkan logika logout di sini
      console.log("Logout clicked!");
      alert("Fungsi logout belum diimplementasikan!");
      // Misalnya:
      // localStorage.removeItem('authToken');
      // this.$router.push('/auth/login');
      this.closeDropdownOnly(); // Gunakan fungsi closeDropdownOnly
    }
  },
  beforeUnmount() {
    // Pastikan Popper dihancurkan saat komponen hilang
    if (this.popperInstance) {
      this.popperInstance.destroy();
      this.popperInstance = null;
    }
  }
};
</script>