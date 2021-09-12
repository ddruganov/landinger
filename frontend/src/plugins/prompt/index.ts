import { App } from "vue";

export default {
  install: (app: App) => {
    app.config.globalProperties.$prompt = (text: string) => new Promise<number>(resolve => {
      window.dispatchEvent(new CustomEvent('prompt.init', {
        detail: {
          text: text
        }
      }));

      const resolveHandler = (event: Event) => {
        resolve((event as CustomEvent).detail.result)
      }
      window.removeEventListener('prompt.choice', resolveHandler);
      window.addEventListener('prompt.choice', resolveHandler);
    });
  },
};

declare module "@vue/runtime-core" {
  //Bind to `this` keyword
  interface ComponentCustomProperties {
    $prompt: (text: string) => Promise<number>;
  }
}
