<template>
  <div class="clipboard-input d-flex align-items-center">
    <input
      type="text"
      :value="value"
      class="form-control form-control-sm w-100"
      :readonly="true"
      role="button"
      @click="copyToClipboard()"
    />
    <div class="button btn-primary rounded-end" @click="copyToClipboard()" role="button">
      <i class="far fa-copy" />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.clipboard-input {
  position: relative;
  * {
    user-select: none !important;
  }
  .button {
    position: absolute;
    top: 0;
    right: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class ClipboardInput extends Vue {
  @Prop(String) readonly value!: string;

  fallbackCopyToClipboard() {
    const textArea = document.createElement("textarea");
    textArea.value = this.value;

    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      const success = document.execCommand("copy");
      success ? this.$notifications.success("Скопировано!") : this.$notifications.error("Ошибка копирования!");
    } catch (err) {
      this.$notifications.error("Ошибка копирования!");
    }

    document.body.removeChild(textArea);
  }
  copyToClipboard() {
    if (!navigator.clipboard) {
      return this.fallbackCopyToClipboard();
    }
    navigator.clipboard.writeText(this.value).then(
      () => this.$notifications.success("Скопировано!"),
      () => this.$notifications.error("Ошибка копирования!")
    );
  }
}
</script>
