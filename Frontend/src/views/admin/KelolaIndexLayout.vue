<template>
  <div class="flex flex-wrap mt-4">
    <div class="w-full px-4">
      <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded-lg bg-white border-0">

        <router-view />

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "KelolaIndexLayout",
  components: {}, // Tidak perlu komponen lain
};
</script>