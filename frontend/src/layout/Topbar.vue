<template>
  <header class="topbar">
    <router-link to="/"><h3 class="brand">Flinq</h3></router-link>
  </header>
</template>

<script lang="ts">
import { authStore } from "@/store/modules/auth.store";
import { Vue } from "vue-class-component";

export default class Topbar extends Vue {
  get authenticatedUser() {
    return authStore.context(this.$store).getters.authenticatedUser;
  }
}
</script>
