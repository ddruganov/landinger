<template>
  <router-view />
  <notifications />
  <button modal-trigger="promptModal" class="d-none">Open Prompt Modal Window</button>
  <prompt-modal />
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import Notifications from "./plugins/notifications/Notifications.vue";
import PromptModal from "./plugins/prompt/PromptModal.vue";

@Options({
  components: { Notifications, PromptModal },
})
export default class App extends Vue {}
</script>
