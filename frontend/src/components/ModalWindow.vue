<template>
  <transition name="fade">
    <div v-if="isShowing" :id="id" @click="(e) => close(e)" class="mw" :class="{ show: isShowing }">
      <div class="backdrop container" :class="{ [modalClass]: modalClass }">
        <div class="content-wrapper">
          <div v-if="!hideHeader" class="header">
            <div class="header-wrapper">
              <slot name="title" />
              <i class="close-icon fas fa-times" @click="(e) => close(e)" />
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
import { Prop, Watch } from "vue-property-decorator";

export default class ModalWindowComponent extends Vue {
  isShowing = false;
  @Prop(Boolean) readonly hideFooter!: boolean;
  @Prop(Boolean) readonly hideHeader!: boolean;
  @Prop(String) readonly modalClass = String("modal_" + Date.now());
  @Prop(String) readonly id!: string;
  @Prop(Number) readonly reload!: number;
  @Watch("reload") onReload() {
    this.load();
  }

  mounted() {
    const body = document.querySelector("body")!;
    const app = body.querySelector("#app")!;
    body.insertBefore(this.$el, app);

    this.load();

    window.addEventListener(`modal.${this.id}.close`, () => {
      this.forceClose(true);
    });
  }

  unmounted() {
    document.querySelector("body")!.removeChild(this.$el);
  }

  load() {
    const trigger = document.querySelector(`[modal-trigger="${this.id}"]`);
    if (!trigger) {
      throw new Error(`Modal trigger for ${this.id} was not found`);
    }
    trigger.removeEventListener("click", () => this.show());
    trigger.addEventListener("click", () => this.show());
  }

  show() {
    this.isShowing = true;
    this.$emit("show");
  }

  close(e: MouseEvent) {
    const containsModalClass = (e.target as HTMLElement).classList.contains(this.modalClass);
    const containsCloseIcon = (e.target as HTMLElement).classList.contains("close-icon");
    if (!containsModalClass && !containsCloseIcon) {
      return;
    }

    this.forceClose(containsCloseIcon);
  }

  forceClose(graceful: boolean) {
    this.isShowing = false;
    this.$emit("close", graceful);
  }
}
</script>
