<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0">
      <div class="text-white ps-3">
        Landinger
      </div>
      <ul class="navbar-nav ms-auto p-3">
        <li class="nav-item position-relative">
          <button class="dropdown-toggler btn btn-primary btn-sm px-4 py-2 lh-normal">
            {{ authenticatedUser.name }}
          </button>

          <div v-show="showDropdown" class="user-dropdown border rounded bg-white">
            <a class="link link-primary" href="/settings/general">Настройки</a>
            <div class="divider border-bottom" />
            <a class="link link-primary" href="/auth/logout">Выход</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>
</template>

<style lang="scss">
.sidebar-toggler {
  font-size: 24px;
  cursor: pointer;
}
.user-dropdown {
  position: absolute;
  right: 0;
  display: flex;
  flex-direction: column;
  .link {
    padding: 0.5rem 2rem;
    text-align: center;
    text-decoration: none;
    &:hover {
      background-color: #eee;
    }
    &:active,
    &:focus {
      background-color: #ddd;
    }
  }
  .divider {
    height: 1px;
    width: 100%;
  }
}
</style>

<script lang="ts">
import { authStore } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";
import OrganizationSelect from "@/components/OrganizationSelect.vue";

@Options({
  components: { OrganizationSelect },
})
export default class Topbar extends Vue {
  get authenticatedUser() {
    return authStore.context(this.$store).getters.authenticatedUser;
  }
  get currentOrganization() {
    return authStore.context(this.$store).getters.currentOrganization;
  }
  get userOrganizations() {
    return authStore.context(this.$store).getters.userOrganizations;
  }

  showDropdown = false;
  showSidebar = false;

  toggleSidebar() {
    this.$emit("toggleSidebar");
  }
}
</script>
