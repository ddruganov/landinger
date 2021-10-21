import appInstance from "@/main";
import { createRouter, createWebHistory, RouteParams, RouteRecordRaw } from "vue-router";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    component: () => import("@/layout/Main.vue"),
    children: [
      /* HOME */
      {
        path: "/",
        meta: { title: "LinkToMe | Лендинги" },
        component: () => import("@/pages/home/Index.vue"),
      },

      /* LANDING */
      {
        path: "/landing/:id/edit",
        meta: { title: "LinkToMe | Редактирование лендинга" },
        component: () => import("@/pages/landing/Edit.vue"),
      },
      {
        path: "/landing/:id/stats",
        meta: { title: "LinkToMe | Статистика лендинга" },
        component: () => import("@/pages/landing/Stats.vue"),
      },

      /* SETTINGS */
      {
        path: "/settings",
        meta: { title: "LinkToMe | Настройки профиля" },
        component: () => import("@/pages/settings/Index.vue"),
      }
    ]
  },
  /* AUTHENTICATION */
  {
    path: "/auth",
    meta: { title: "LinkToMe | Авторизация" },
    component: () => import("@/layout/Auth.vue"),
    children: [
      {
        path: "/auth/login",
        meta: { title: "LinkToMe | Вход" },
        component: () => import("@/pages/auth/Login.vue"),
        props: (route: RouteParams) => ({ backurl: (route.query as any).backurl }),
      },
      {
        path: "/auth/register",
        meta: { title: "LinkToMe | Регистрация" },
        component: () => import("@/pages/auth/Register.vue"),
        props: (route: RouteParams) => ({ hash: (route.query as any).hash }),
      },
      {
        path: "/auth/logout",
        meta: { title: "LinkToMe | Выход" },
        component: () => import("@/pages/auth/Logout.vue"),
      },
      {
        path: "/auth/social/:socialNetworkAlias",
        meta: { title: "LinkToMe | Авторизация через соцсеть" },
        component: () => import("@/pages/auth/SocialNetwork.vue"),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});
router.afterEach((to) => {
  appInstance.$nextTick(() => {
    document.title = to.meta.title as string;
  });
});

export default router;
