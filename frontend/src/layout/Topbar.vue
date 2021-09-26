<template>
  <header class="topbar">
    <router-link to="/"><h3 class="brand">Flinq</h3></router-link>

    <button class="button wfc ms-auto" @click="() => logout()">
      Выйти
    </button>
  </header>
</template>

<script lang="ts">
import Api from "@/common/api";
import { authStore } from "@/store/modules/auth.store";
import { Vue } from "vue-class-component";

export default class Topbar extends Vue {
  get authenticatedUser() {
    return authStore.context(this.$store).getters.authenticatedUser;
  }

  private logout() {
    Api.auth.logout().then(() => {
      this.$router.push("/auth/login");
    });
  }
}
</script>
