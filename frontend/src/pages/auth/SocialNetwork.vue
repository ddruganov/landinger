<template>
  Подождите, пока производится авторизация через соцсеть
</template>

<script lang="ts">
import Api from "@/common/api";
import { Vue } from "vue-class-component";

export default class SocialNetwork extends Vue {
  private get socialNetworkAlias() {
    return this.$router.currentRoute.value.params.socialNetworkAlias;
  }

  mounted() {
    const query: any = {};
    new URL(window.location.href).searchParams.forEach((value, key) => {
      query[key] = value;
    });
    query.alias = this.socialNetworkAlias;

    Api.auth
      .socialNetworkAuth(query)
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.$router.push("/");
      })
      .catch(() => {
        this.$notifications.error("Ошибка авторизации через соцсеть");
        this.$router.push("/auth/login");
      });
  }
}
</script>
