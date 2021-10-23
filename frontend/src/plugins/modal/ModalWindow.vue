<template>
  <transition name="fade">
    <div v-if="isOpened" :id="id" @click="(e) => onOutsideClick(e)" class="mw" :class="{ show: isOpened }">
      <div class="backdrop container" :class="{ [modalClass]: modalClass }">
        <div class="content-wrapper">
          <div v-if="!hideHeader" class="header">
            <div class="header-wrapper">
              <slot name="title" />
              <i class="close-icon fas fa-times" @click="(e) => onOutsideClick(e)" />
            </div>
          </div>
          <div class="body">
            <slot name="body" />
          </div>
          <div v-if="!hideFooter" class="footer">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.125s linear;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class ModalWindowComponent extends Vue {
  private isOpened = false;
  @Prop(Boolean) readonly hideFooter!: boolean;
  @Prop(Boolean) readonly hideHeader!: boolean;
  @Prop(String) readonly modalClass = String("modal_" + Date.now());
  @Prop(String) readonly id!: string;

  mounted() {
    const body = document.querySelector("body")!;
    const app = body.querySelector("#app")!;
    body.insertBefore(this.$el, app);

    window.addEventListener(`modal.${this.id}.open`, () => {
      this.open();
    });

    window.addEventListener(`modal.${this.id}.close`, () => {
      this.forceClose(true);
    });
  }

  unmounted() {
    document.querySelector("body")!.removeChild(this.$el);
  }

  open() {
    this.isOpened = true;
    this.$emit("open");
  }

  onOutsideClick(e: MouseEvent) {
    const containsModalClass = (e.target as HTMLElement).classList.contains(this.modalClass);
    const containsCloseIcon = (e.target as HTMLElement).classList.contains("close-icon");
    if (!containsModalClass && !containsCloseIcon) {
      return;
    }

    this.forceClose(containsCloseIcon);
  }

  forceClose(graceful: boolean) {
    this.isOpened = false;
    this.$emit("close", graceful);
  }
}
</script>
