<template>
  <footer class="footer">
    <span class="text-muted">{{ fullYear }} Flinq</span>
  </footer>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class FooterComponent extends Vue {
  get fullYear() {
    return new Date().getFullYear();
  }
}
</script>
