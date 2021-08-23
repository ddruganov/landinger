<template>
  <h3 class="text-center">Вход</h3>
  <div class="email mb-1">
    <input class="form-control" type="email" placeholder="Введите email" v-model="credentials.email" />
    <div class="error" v-if="errors.email">
      {{ errors.email }}
    </div>
  </div>
  <div class="password mb-1">
    <input class="form-control" placeholder="Введите пароль" type="password" v-model="credentials.password" />
    <div class="error" v-if="errors.password">
      {{ errors.password }}
    </div>
  </div>
  <button :disabled="requestCommencing" class="btn btn-block btn-primary lh-normal w-100 mb-3" @click="login()">
    Войти
  </button>
  <div class="d-flex align-items-center justify-content-between">
    <a href="/auth/restore" class="link-primary">Забыли пароль?</a>
    <a href="/auth/register" class="link-primary">Регистрация</a>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import { authStore, SET_AUTHENTICATED } from "@/store/modules/auth.store";
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

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
