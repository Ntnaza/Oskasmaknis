import { createApp } from "vue";
import { createPinia } from "pinia";
import { createWebHistory, createRouter } from "vue-router";

// styles
import "@fortawesome/fontawesome-free/css/all.min.css";
import "@/assets/styles/tailwind.css";

// mouting point for the whole app
import App from "@/App.vue";

// layouts
import Admin from "@/layouts/Admin.vue";
import Auth from "@/layouts/Auth.vue";

// views for Admin layout
import Dashboard from "@/views/admin/Dashboard.vue";
import Settings from "@/views/admin/Settings.vue";
import Tables from "@/views/admin/Tables.vue";
import Maps from "@/views/admin/Maps.vue";
// --- Import Layouts Baru ---
import KelolaLandingLayout from "@/views/admin/KelolaLandingLayout.vue";
import KelolaIndexLayout from "@/views/admin/KelolaIndexLayout.vue";
// --- Komponen CRUD Landing ---
import ManageLanding from "@/views/admin/ManageLanding.vue";
import ManageFeatures from "@/views/admin/ManageFeatures.vue";
import ManagePromo from "@/views/admin/ManagePromo.vue";
import ManageTeam from "@/views/admin/ManageTeam.vue";
// --- Halaman admin lainnya ---
import ManageProfile from "@/views/admin/ManageProfile.vue";
import ManageWorkPrograms from "@/views/admin/ManageWorkPrograms.vue";
import ManageArticles from "@/views/admin/ManageArticles.vue";
import ManageIndex from "@/views/admin/ManageIndex.vue"; // Tetap diimport

// views for Auth layout
import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";

// views without layouts
import Landing from "@/views/Landing.vue";
import Profile from "@/views/Profile.vue";
import Index from "@/views/Index.vue";
import WorkPrograms from "@/views/WorkPrograms.vue";
import AnggotaDetail from "@/views/AnggotaDetail.vue";
import ArticlesIndex from "@/views/ArticlesIndex.vue";
import BeritaDetail from "@/views/BeritaDetail.vue";

// routes
const routes = [
  {
    path: "/admin",
    redirect: "/admin/dashboard",
    component: Admin,
    children: [
      {
        path: "/admin/dashboard",
        component: Dashboard,
        name: "admin-dashboard", // Beri nama untuk konsistensi
      },
      {
        path: "/admin/settings",
        component: Settings,
        name: "admin-settings",
      },
      {
        path: "/admin/tables",
        component: Tables,
        name: "admin-tables",
      },
      {
        path: "/admin/maps",
        component: Maps,
        name: "admin-maps",
      },
      // --- ROUTE INDUK BARU UNTUK KELOLA LANDING ---
      {
        path: "/admin/kelola-landing",
        component: KelolaLandingLayout, // Gunakan layout baru
        redirect: "/admin/kelola-landing/hero", // Arahkan ke tab pertama
        children: [ // Ini adalah tab-tabnya
          {
            path: "hero", // Path relatif -> /admin/kelola-landing/hero
            name: "admin-kelola-landing-hero", // Beri nama agar mudah di-link
            component: ManageLanding, // Komponen CRUD Hero/Landing
          },
          {
            path: "fitur", // Path relatif -> /admin/kelola-landing/fitur
            name: "admin-kelola-landing-fitur",
            component: ManageFeatures, // Komponen CRUD Fitur
          },
          {
            path: "promo", // Path relatif -> /admin/kelola-landing/promo
            name: "admin-kelola-landing-promo",
            component: ManagePromo, // Komponen CRUD Promo
          },
          {
            path: "tim", // Path relatif -> /admin/kelola-landing/tim
            name: "admin-kelola-landing-tim",
            component: ManageTeam, // Komponen CRUD Tim
          },
        ],
      },
      // --- ROUTE UNTUK KELOLA INDEX DIPERBARUI ---
      {
        path: "/admin/manage-index",
        component: KelolaIndexLayout, // Gunakan layout baru
        children: [
          {
            path: "", // Path kosong, jadi ini yang dirender untuk /admin/manage-index
            name: "admin-kelola-index-main", // Beri nama
            component: ManageIndex, // Komponen asli yang berisi tab internal
          },
          // Jika nanti ingin memecah tab index menjadi child route terpisah, bisa ditambahkan di sini
        ],
      },
      // --- Halaman admin lainnya ---
      {
        path: "/admin/manage-profile",
        component: ManageProfile,
        name: "admin-manage-profile",
      },
      {
        path: "/admin/manage-work-programs",
        component: ManageWorkPrograms,
        name: "admin-manage-work-programs",
      },
      {
        path: "/admin/manage-articles",
        component: ManageArticles,
        name: "admin-manage-articles",
      },
    ],
  },
  {
    path: "/auth",
    redirect: "/auth/login",
    component: Auth,
    children: [
      {
        path: "/auth/login",
        component: Login,
        name: "login",
      },
      {
        path: "/auth/register",
        component: Register,
        name: "register",
      },
    ],
  },
  {
    path: "/landing",
    component: Landing,
    name: "landing",
  },
  {
    path: "/profile", // Mungkin halaman profil publik?
    component: Profile,
    name: "profile-public",
  },
  {
    path: "/program-kerja",
    component: WorkPrograms,
    name: "program-kerja",
  },
   {
    path: "/anggota/:id",
    name: "anggota-detail",
    component: AnggotaDetail,
    props: true, // Kirim parameter 'id' sebagai prop
  },
  {
    path: "/berita-dan-galeri",
    component: ArticlesIndex,
    name: "berita-dan-galeri",
  },
  {
    path: "/berita/:slug",
    name: "berita-detail",
    component: BeritaDetail,
    props: true, // Kirim parameter 'slug' sebagai prop
  },
  {
    path: "/",
    component: Index,
    name: "index",
  },
  { path: "/:pathMatch(.*)*", redirect: "/" }, // Catch-all route
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  // Opsi tambahan: scroll ke atas saat pindah halaman
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);

app.mount("#app");