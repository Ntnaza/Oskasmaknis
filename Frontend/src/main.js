import { createApp } from "vue";
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
import ManageLanding from "@/views/admin/ManageLanding.vue";
import ManageFeatures from "@/views/admin/ManageFeatures.vue";
import ManagePromo from "@/views/admin/ManagePromo.vue";
import ManageTeam from "@/views/admin/ManageTeam.vue";
import ManageProfile from "@/views/admin/ManageProfile.vue";
import ManageWorkPrograms from "@/views/admin/ManageWorkPrograms.vue";
import ManageArticles from "@/views/admin/ManageArticles.vue";


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
import ArticleDetail from "@/views/ArticleDetail.vue";
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
      },
      {
        path: "/admin/settings",
        component: Settings,
      },
      {
        path: "/admin/tables",
        component: Tables,
      },
      {
        path: "/admin/maps",
        component: Maps,
      },
      { 
        path: "/admin/manage-landing",
        component: ManageLanding,
      },
      {
        path: "/admin/manage-features",
        component: ManageFeatures,
      },
      {
        path: "/admin/manage-promo",
        component: ManagePromo,
      },
      {
        path: "/admin/manage-team",
        component: ManageTeam,
      },
      {
        path: "/admin/manage-profile",
        component: ManageProfile,
      },
      {
        path: "/admin/manage-work-programs",
        component: ManageWorkPrograms,
      },
      {
        path: "/admin/manage-articles",
        component: ManageArticles,
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
      },
      {
        path: "/auth/register",
        component: Register,
      },
    ],
  },
  {
    path: "/landing",
    component: Landing,
  },
  {
    path: "/profile",
    component: Profile,
  },
  {
    path: "/program-kerja",
    component: WorkPrograms,
  },
   {
    // :id adalah parameter dinamis
    path: "/anggota/:id",
    name: "anggota-detail",
    component: AnggotaDetail,
  },
  {
    path: "/berita-dan-galeri",
    component: ArticlesIndex,
  },
  {
    path: "/berita/:slug",
    name: "artikel-detail",
    component: ArticleDetail,
  },
  {
    path: "/",
    component: Index,
  },
  { path: "/:pathMatch(.*)*", redirect: "/" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

createApp(App).use(router).mount("#app");

