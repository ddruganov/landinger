import { createRouter, createWebHistory, RouteParams, RouteRecordRaw } from "vue-router";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    component: () => import("@/layout/Main.vue"),
    children: [
      /* HOME */
      {
        path: "/",
        component: () => import("@/pages/home/Index.vue"),
      },

      /* LANDING */
      // {
      //   path: "/activities",
      //   component: () => import("@/pages/landing/Edit.vue"),
      // },
    ]
  },
  /* AUTHENTICATION */
  {
    path: "/auth",
    component: () => import("@/layout/Auth.vue"),
    children: [
      {
        path: "/auth/login",
        component: () => import("@/pages/auth/Login.vue"),
        props: (route: RouteParams) => ({ backurl: (route.query as any).backurl }),
      },
      {
        path: "/auth/register",
        component: () => import("@/pages/auth/Register.vue"),
        props: (route: RouteParams) => ({ hash: (route.query as any).hash }),
      },
      {
        path: "/auth/logout",
        component: () => import("@/pages/auth/Logout.vue"),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
