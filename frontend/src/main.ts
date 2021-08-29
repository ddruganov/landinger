import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import { store } from "./store";

import "@/scss/index.scss";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";

import "@fortawesome/fontawesome-free/css/all.css";
import notifications from "./plugins/notifications";

const appInstance = createApp(App)
  .use(store)
  .use(router)
  .use(notifications)
  .mount("#app");

export default appInstance;