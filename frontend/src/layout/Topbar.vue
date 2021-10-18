<template>
  <header class="topbar">
    <div class="container">
      <router-link to="/"><h3 class="brand">LinkToMe</h3></router-link>

      <button class="button wfc ms-auto" @click="() => logout()">
        Выйти
      </button>
    </div>
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
