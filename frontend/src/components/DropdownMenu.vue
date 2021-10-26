<template>
  <div :id="id" class="dropdown-menu-wrapper">
    <button class="button">
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
}
</script>
