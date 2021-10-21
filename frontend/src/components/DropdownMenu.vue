<template>
  <div :id="id" class="dropdown-menu-wrapper" @mouseleave="() => close()">
    <button class="button" @click="() => open()">
      <slot name="caption" />
    </button>
    <div class="dropdown-wrapper block">
      <slot name="content" />
    </div>
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class DropdownMenu extends Vue {
  private id = "DropdownMenu_" + Date.now();

  private get container() {
    return document.getElementById(this.id)!;
  }

  private open() {
    this.container.classList.add("open");
  }

  private close() {
    this.container.classList.remove("open");
  }
}
</script>
