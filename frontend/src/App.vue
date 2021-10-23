<template>
  <router-view />
  <notifications />
  <prompt-modal />
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import Notifications from "./plugins/notifications/Notifications.vue";
import PromptModal from "./plugins/prompt/PromptModal.vue";

@Options({
  components: { Notifications, PromptModal },
})
export default class App extends Vue {
  mounted() {
    window.addEventListener("dragover", (e: DragEvent) => {
      e.preventDefault();
    });
  }
}
</script>
