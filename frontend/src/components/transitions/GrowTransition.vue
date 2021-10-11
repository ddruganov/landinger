<template>
  <transition
    name="grow"
    @beforeEnter="beforeEnter"
    @afterEnter="afterEnter"
    @beforeLeave="beforeLeave"
    @afterLeave="afterLeave"
  >
    <slot />
  </transition>
</template>

<style lang="scss">
.grow-enter-active,
.grow-leave-active {
  transition: all 0.25s linear;
  height: 100%;
  overflow: hidden;
  opacity: 1;
}

.grow-enter-from,
.grow-leave-to {
  height: 0;
  opacity: 0;
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class GrowTransition extends Vue {
  beforeEnter(el: HTMLElement) {
    // Setup clone
    const clone = el.cloneNode(true) as HTMLElement;
    clone.style.width = el.style.width;
    clone.style.visibility = "hidden";
    clone.style.removeProperty("display");

    // get clone height
    el?.parentNode?.appendChild(clone);
    const height = clone.clientHeight;
    clone.remove();

    // Force animation instead of simple "setting a height"
    el.style.height = "0px";
    setTimeout(() => (el.style.height = height + "px"), 1);
  }
  afterEnter(el: HTMLElement) {
    el.style.removeProperty("height");
  }
  beforeLeave(el: HTMLElement) {
    el.style.height = el.clientHeight + "px";
    setTimeout(() => (el.style.height = "0px"), 1);
  }
  afterLeave(el: HTMLElement) {
    el.style.removeProperty("height");
  }
}
</script>
