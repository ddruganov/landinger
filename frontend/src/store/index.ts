import { createStore, Module } from "vuex-smart-module";
import { authStore } from "@/store/modules/auth.store";
import { requestStore } from "./modules/request.store";
import { landingStore } from "./modules/landing.store";

export const store = createStore(
  new Module({
    modules: {
      auth: authStore,
      landing: landingStore,
      request: requestStore
    },
  })
);
