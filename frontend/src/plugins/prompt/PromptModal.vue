<template>
  <button ref="trigger" modal-trigger="promptModal" class="d-none">prompt trigger</button>
  <modal-window id="promptModal">
    <template #title>
      <span>Подтвердите действие</span>
    </template>
    <template #body>
      <div v-html="text" />
    </template>
    <template #footer>
      <button class="button ms-auto wfc" @click="() => declinePrompt()">
        Отмена
      </button>
      <button class="button ms-3 wfc" @click="() => acceptPrompt()">OK</button>
    </template>
  </modal-window>
</template>

<script lang="ts">
import ModalWindow from "@/components/ModalWindow.vue";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { ModalWindow },
})
export default class PromptModal extends Vue {
  private text!: string;

  mounted() {
    this.text = "";
    window.addEventListener("prompt.init", (event: Event) => {
      this.text = (event as CustomEvent).detail.text;
      (document.querySelector('[modal-trigger="promptModal"]') as HTMLButtonElement).click();
    });
  }
  acceptPrompt() {
    this.resolvePrompt(1);
  }

  declinePrompt() {
    this.resolvePrompt(0);
  }

  resolvePrompt(result: number) {
    window.dispatchEvent(new CustomEvent("prompt.choice", { detail: { result: result } }));
    window.dispatchEvent(new CustomEvent("modal.promptModal.close"));
  }
}
</script>
