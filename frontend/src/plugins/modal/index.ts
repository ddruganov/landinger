import { App } from "vue";

type ModalPlugin = {
  open: (id: string) => void;
  close: (id: string) => void;
};

export default {
  install: (app: App) => {
    app.config.globalProperties.$modal = {
      open: (id: string) => {
        window.dispatchEvent(new CustomEvent(`modal.${id}.open`));
      },
      close: (id: string) => {
        window.dispatchEvent(new CustomEvent(`modal.${id}.close`));
      },
    };
  },
};

declare module "@vue/runtime-core" {
  //Bind to `this` keyword
  interface ComponentCustomProperties {
    $modal: ModalPlugin;
  }
}
