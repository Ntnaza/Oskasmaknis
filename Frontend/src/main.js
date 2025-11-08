import { createApp } from "vue";
import { createPinia } from "pinia";
import { createWebHistory, createRouter } from "vue-router";
import axios from "axios"; // <-- IMPORT AXIOS

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
// --- Layouts ---
import KelolaLandingLayout from "@/views/admin/KelolaLandingLayout.vue";
import KelolaIndexLayout from "@/views/admin/KelolaIndexLayout.vue";
// --- Komponen CRUD Landing ---
import ManageLanding from "@/views/admin/ManageLanding.vue";
import ManageFeatures from "@/views/admin/ManageFeatures.vue";
import ManagePromo from "@/views/admin/ManagePromo.vue";
import ManageTeam from "@/views/admin/ManageTeam.vue";
// --- Komponen CRUD Index ---
import ManageIndexHero from "@/views/admin/index-tabs/ManageIndexHero.vue";
import ManageIndexSambutan from "@/views/admin/index-tabs/ManageIndexSambutan.vue";
import ManageIndexPembina from "@/views/admin/index-tabs/ManageIndexPembina.vue";
import ManageIndexJurusan from "@/views/admin/index-tabs/ManageIndexJurusan.vue";
import ManageIndexSejarah from "@/views/admin/index-tabs/ManageIndexSejarah.vue";
import ManageIndexAjakan from "@/views/admin/index-tabs/ManageIndexAjakan.vue";
// --- Halaman admin lainnya ---
import ManageProfile from "@/views/admin/ManageProfile.vue";
import ManageWorkPrograms from "@/views/admin/ManageWorkPrograms.vue";
import ManageArticles from "@/views/admin/ManageArticles.vue";
// --- Halaman Event ---
import ManageEvents from "@/views/admin/ManageEvents.vue";
import EventDetail from "@/views/admin/EventDetail.vue";

import MemberCardGenerator from "@/views/admin/MemberCardGenerator.vue";
import CardPrintPage from "@/views/admin/CardPrintPage.vue";

// --- 1. IMPORT BARU UNTUK ADMIN ASPIRASI ---
import ManageAspirations from "@/views/admin/ManageAspirations.vue";

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
// (ScanAttendance.vue sudah dihapus, bagus)

// --- IMPORT BARU UNTUK ASPIRASI ---
import SubmitAspiration from "@/views/SubmitAspiration.vue";
import TrackAspiration from "@/views/TrackAspiration.vue";
// ----------------------------------

// --- KONFIGURASI BASE URL GLOBAL AXIOS ---
axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL || 'http://localhost:8000';
// --- BATAS KONFIGURASI ---

// routes
const routes = [
  {
    path: "/admin/kartu-cetak",
    component: CardPrintPage,
    name: "admin-kartu-cetak",
  },
  {
    path: "/admin",
    redirect: "/admin/dashboard",
    component: Admin,
    children: [
      {
        path: "/admin/dashboard",
        component: Dashboard,
        name: "admin-dashboard",
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
      {
        path: "/admin/kelola-landing",
        component: KelolaLandingLayout,
        redirect: "/admin/kelola-landing/hero",
        children: [
          { path: "hero", name: "admin-kelola-landing-hero", component: ManageLanding },
          { path: "fitur", name: "admin-kelola-landing-fitur", component: ManageFeatures },
          { path: "promo", name: "admin-kelola-landing-promo", component: ManagePromo },
          { path: "tim", name: "admin-kelola-landing-tim", component: ManageTeam },
        ],
      },
      {
        path: "/admin/manage-index",
        component: KelolaIndexLayout,
        redirect: "/admin/manage-index/hero",
        children: [
          { path: "hero", name: "admin-index-hero", component: ManageIndexHero },
          { path: "sambutan", name: "admin-index-sambutan", component: ManageIndexSambutan },
          { path: "pembina", name: "admin-index-pembina", component: ManageIndexPembina },
          { path: "jurusan", name: "admin-index-jurusan", component: ManageIndexJurusan },
          { path: "sejarah", name: "admin-index-sejarah", component: ManageIndexSejarah },
          { path: "ajakan", name: "admin-index-ajakan", component: ManageIndexAjakan },
        ],
      },
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
      {
        path: "/admin/manage-events", 
        component: ManageEvents,
        name: "admin-manage-events",
      },
      {
        path: "/admin/event/:id", 
        component: EventDetail,
        name: "admin-event-detail",
        props: true 
      },
      {
        path: "/admin/kartu-anggota",
        component: MemberCardGenerator,
        name: "admin-kartu-anggota",
      },
      // --- 2. RUTE BARU DITAMBAHKAN DI SINI ---
      {
        path: "/admin/manage-aspirations",
        component: ManageAspirations,
        name: "admin-manage-aspirations",
      },
      // -------------------------------------
    ],
  },
  {
    path: "/auth",
    redirect: "/auth/login",
    component: Auth,
    children: [
      { path: "/auth/login", component: Login, name: "login" },
      { path: "/auth/register", component: Register, name: "register" },
    ],
  },
  { path: "/landing", component: Landing, name: "landing" },
  { path: "/profile", component: Profile, name: "profile-public" },
  { path: "/program-kerja", component: WorkPrograms, name: "program-kerja" },
  { path: "/anggota/:id", name: "anggota-detail", component: AnggotaDetail, props: true },
  { path: "/berita-dan-galeri", component: ArticlesIndex, name: "berita-dan-galeri" },
  { path: "/berita/:slug", name: "berita-detail", component: BeritaDetail, props: true },
  { path: "/", component: Index, name: "index" },
  
  // --- RUTE PUBLIK ASPIRASI (Sudah benar) ---
  { 
    path: "/aspirasi", 
    component: SubmitAspiration, 
    name: "submit-aspirasi",
  },
  { 
    path: "/aspirasi/lacak", 
    component: TrackAspiration, 
    name: "track-aspirasi-form",
  },
  { 
    path: "/aspirasi/lacak/:ticket_id", 
    component: TrackAspiration, 
    name: "track-aspirasi-detail", 
    props: true 
  },
  // ---------------------------------

  { path: "/:pathMatch(.*)*", redirect: "/" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
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