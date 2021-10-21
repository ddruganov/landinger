<template>
  <header class="topbar">
    <div class="container p-0">
      <router-link to="/" class="p-3"><h3 class="brand">LinkToMe</h3></router-link>

      <dropdown-menu class="ms-auto">
        <template #caption>
          <div class="content-container">
            <span class="username">{{ authenticatedUser.name }}</span>
            <img class="avatar" :src="authenticatedUser.image.url" />
          </div>
        </template>
        <template #content>
          <router-link class="button mb-3" to="/settings">
            Настройки
          </router-link>
          <button class="button" @click="() => logout()">
            Выйти
          </button>
        </template>
      </dropdown-menu>
    </div>
  </header>
</template>

<script lang="ts">
import Api from "@/common/api";
import DropdownMenu from "@/components/DropdownMenu.vue";
import { authStore } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { DropdownMenu },
})
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
