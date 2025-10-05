<template>
  <div
    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
  >
    <div class="rounded-t mb-0 px-4 py-3 border-0">
      <div class="flex flex-wrap items-center">
        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
          <h3 class="font-semibold text-base text-blueGray-700">
            Aku berhasil mengubah ini
          </h3>
        </div>
        <div
          class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
        >
          <button
            class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
            type="button"
          >
            See all
          </button>
        </div>
      </div>
    </div>
    <div class="block w-full overflow-x-auto">
      <!-- Projects table -->
      <table class="items-center w-full bg-transparent border-collapse">
        <thead>
          <tr>
            <th
              class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
            >
              Page name
            </th>
            <th
              class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
            >
              Visitors
            </th>
            <th
              class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
            >
              Unique users
            </th>
            <th
              class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
            >
              Bounce rate
            </th>
          </tr>
        </thead>
        <tbody>
  <tr v-for="item in tableData" :key="item.page">
    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
      {{ item.page }}
    </th>
    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
      {{ item.visitors }}
    </td>
    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
      {{ item.users }}
    </td>
    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
      <i :class="`fas fa-arrow-${item.direction} text-${item.color}-500 mr-4`"></i>
      {{ item.rate }}
    </td>
  </tr>
</tbody>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      // Di sinilah kita akan menyimpan data tabel
      tableData: [
        {
          page: "/argon/",
          visitors: "4,569",
          users: "340",
          rate: "46,53%",
          direction: "up",
          color: "emerald",
        },
        {
          page: "/argon/index.html",
          visitors: "3,985",
          users: "319",
          rate: "46,53%",
          direction: "down",
          color: "orange",
        },
        {
          page: "/argon/charts.html",
          visitors: "3,513",
          users: "294",
          rate: "36,49%",
          direction: "down",
          color: "orange",
        },
        {
          page: "/argon/tables.html",
          visitors: "2,050",
          users: "147",
          rate: "50,87%",
          direction: "up",
          color: "emerald",
        },
        {
          page: "/argon/profile.html",
          visitors: "1,795",
          users: "190",
          rate: "46,53%",
          direction: "down",
          color: "red",
        },
      ],
    };
  },
};
</script>