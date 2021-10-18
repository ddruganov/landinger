<template>
  <div class="login block column">
    <h3 class="caption">Вход в LinkToMe</h3>

    <form-input v-model="credentials.email" label="email" :error="errors.email" />
    <form-input v-model="credentials.password" label="пароль" :error="errors.password" type="password" />

    <button :disabled="requestCommencing" class="login button" @click="() => login()">
      Войти
    </button>

    <div class="links">
      <router-link class="link" to="/auth/register">регистрация</router-link>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import FormInput from "@/components/form/FormInput.vue";
import { authStore, SET_AUTHENTICATED } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

@Options({
  components: { FormInput },
})
export default class AuthLogin extends Vue {
  @Prop(String) readonly backurl?: string;

  credentials = {
    email: "",
    password: "",
  };
  errors = [];
  requestCommencing = false;

  get isAuthenticated() {
    return authStore.context(this.$store).getters.isAuthenticated;
  }

  login() {
    this.errors = [];
    this.requestCommencing = true;
    Api.auth
      .login(this.credentials)
      .then((response) => {
        authStore.context(this.$store).dispatch(SET_AUTHENTICATED, response.success);
        this.isAuthenticated ? this.$router.push({ path: this.backurl || "/" }) : (this.errors = response.data.errors);
      })
      .finally(() => {
        this.requestCommencing = false;
      });
  }
}
</script>
