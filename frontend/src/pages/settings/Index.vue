<template>
  <div class="user-settings container my-5">
    <div class="block column">
      <h3 class="mb-3">Настройки профиля</h3>

      <div class="main-settings d-flex w-100">
        <image-upload showDeleteButton v-model="authenticatedUser.image" label="аватар" @change="() => save()" />
        <div class="d-flex flex-column ms-3 w-100">
          <form-input class="mb-3" v-model="authenticatedUser.name" label="имя" disabled />
          <form-input class="mb-3" v-model="authenticatedUser.email" label="email" disabled />
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import FormInput from "@/components/form/FormInput.vue";
import ImageUpload from "@/components/ImageUpload.vue";
import { authStore } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { FormInput, ImageUpload },
})
export default class SettingsIndex extends Vue {
  get authenticatedUser() {
    return authStore.context(this.$store).getters.authenticatedUser;
  }

  private save() {
    Api.user
      .save(this.authenticatedUser)
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.$notifications.success("Сохранено");
      })
      .catch(() => this.$notifications.error("Ошибка сохранения настроек профиля"));
  }
}
</script>
