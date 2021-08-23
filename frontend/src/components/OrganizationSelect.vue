<template>
  <div v-if="currentOrganization" class="organization d-flex align-items-center" modal-trigger="userOrgSelect">
    <img class="image" :src="currentOrganization.logo.url" />
    <div class="organization-select">
      {{ currentOrganization.name }}
    </div>
  </div>

  <modal-window id="userOrgSelect" modalClass="user-org-select-modal">
    <template #title>
      <h5 class="modal-title">Выберите организацию</h5>
    </template>
    <template #body>
      <div
        v-for="org in userOrganizations"
        :key="org.id"
        class="org d-flex align-items-center p-1 rounded-10"
        @click="changeOrganization(org.id)"
      >
        <img :src="org.logo.url" class="logo" />
        <span class="ms-3">{{ org.name }}</span>
        <div v-if="org.isDefault" class="ms-auto me-2 default" />
      </div>
    </template>
  </modal-window>
</template>

<style lang="scss" scoped>
.user-org-select-modal {
  .org {
    cursor: pointer;
    &:hover {
      background: #eee;
    }
    .logo {
      width: 40px;
      height: 40px;
      border-radius: 100%;
      background: white;
      border: 1px solid white;
    }
    .default {
      width: 10px;
      height: 10px;
      border-radius: 100%;
      background: #007bff;
    }
  }
}

.organization {
  width: 250px;
  height: 71px;
  position: relative;
  cursor: pointer;
  .image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .organization-select {
    position: absolute;
    background-color: rgba(black, 0.5);
    color: white;
    text-indent: 10px;
    width: 100%;
    height: 100%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>

<script lang="ts">
import Api from "@/common/api";
import { authStore } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";
import ModalWindow from "@/components/ModalWindow.vue";

@Options({
  components: { ModalWindow },
})
export default class OrganizationSelect extends Vue {
  get currentOrganization() {
    return authStore.context(this.$store).getters.currentOrganization;
  }
  get userOrganizations() {
    return authStore.context(this.$store).getters.userOrganizations;
  }

  changeOrganization(organizationId: number) {
    Api.settings.organization.setDefault(organizationId).then((response) => {
      response.success ? location.reload() : this.$notifications.error("Ошибка смены организации");
    });
  }
}
</script>
