<template>
  <button ref="promptModalTrigger" class="d-none" @click="() => showModal()" />
  <modal-window :id="id">
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
import ModalWindow from "@/plugins/modal/ModalWindow.vue";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { ModalWindow },
})
export default class PromptModal extends Vue {
  private text!: string;
  private id: string = "promptModal";

  mounted() {
    this.text = "";

    window.addEventListener("prompt.init", (event: Event) => {
      this.text = (event as CustomEvent).detail.text;
      (this.$refs.promptModalTrigger as HTMLButtonElement).click();
    });
  }
  acceptPrompt() {
    this.resolvePrompt(1);
  }

  declinePrompt() {
    this.resolvePrompt(0);
  }

  showModal() {
    this.$modal.open(this.id);
  }

  resolvePrompt(result: number) {
    window.dispatchEvent(new CustomEvent("prompt.choice", { detail: { result: result } }));
    this.$modal.close(this.id);
  }
}
</script>
