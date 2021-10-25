<template>
  <div class="main-settings">
    <image-upload showDeleteButton v-model="authenticatedUser.image" label="аватар" @change="() => save()" />
    <div class="info-inputs">
      <form-input class="mb-3" v-model="authenticatedUser.name" label="имя" disabled />
      <form-input class="mb-3" v-model="authenticatedUser.email" label="email" disabled />
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
export default class MainSettings extends Vue {
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
