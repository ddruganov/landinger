<template>
  <div class="login block column">
    <h3 class="caption">Вход в LinkToMe</h3>

    <div class="social-auth">
      <a class="social-network" v-for="socialLink in socialLinks" :key="socialLink.alias" :href="socialLink.value">
        <img class="logo" :src="`/assets/social/${socialLink.alias}.svg`" />
      </a>
    </div>

    <div class="links">
      <router-link class="link" to="/auth/register">регистрация</router-link>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import FormInput from "@/components/form/FormInput.vue";
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

type SocialLink = {
  alias: string;
  value: string;
};

@Options({
  components: { FormInput },
})
export default class AuthLogin extends Vue {
  @Prop(String) readonly backurl?: string;

  socialLinks: SocialLink[] = [];

  mounted() {
    Api.auth
      .getSocialLinks()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.socialLinks = response.data;
      })
      .catch(() => this.$notifications.error("Ошибка получения ссылок на соцсети"));
  }
}
</script>
