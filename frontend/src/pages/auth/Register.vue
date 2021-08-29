<template>
  <h3 class="text-center">Регистрация</h3>
  <div class="email mb-1">
    <input class="form-control" placeholder="Введите имя" v-model="form.name" />
    <div class="error" v-if="errors.name">
      {{ errors.name }}
    </div>
  </div>
  <div class="email mb-1">
    <input class="form-control" placeholder="Введите email" type="email" v-model="form.email" />
    <div class="error text-danger mb-1">
      {{ errors.email }}
    </div>
  </div>
  <div class="password mb-1">
    <input type="password" class="form-control" placeholder="Пароль" v-model="form.password" />
    <div class="error text-danger mb-1">
      {{ errors.password }}
    </div>
  </div>
  <div class="password mb-1">
    <input type="password" class="form-control" placeholder="Повторите пароль" v-model="form.repeatPassword" />
    <div class="error text-danger mb-1">
      {{ errors.repeatPassword }}
    </div>
  </div>
  <div class="terms mb-1">
    <custom-switch v-model="form.agreedToTerms" :size="16">
      Я прочитал и согласен с <a href="/" class="link-primary">условиями пользования сервисом</a>
    </custom-switch>
    <div class="error text-danger mb-1">
      {{ errors.agreedToTerms }}
    </div>
  </div>
  <button class="btn btn-block btn-primary lh-normal w-100 mb-3" @click="register()">Зарегистрироваться</button>
  <div class="d-flex align-items-center justify-content-between">
    <a href="/auth/login" class="link-primary">Вход</a>
    <a href="/auth/restore" class="link-primary">Забыли пароль?</a>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import FormSwitch from "@/components/form/FormSwitch.vue";
import RegisterData from "@/types/auth/RegisterData";
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

@Options({
  components: { FormSwitch },
})
export default class AuthRegister extends Vue {
  @Prop(String) readonly hash?: string;

  form: RegisterData = {
    name: "",
    email: "",
    password: "",
    repeatPassword: "",
    agreedToTerms: false,
    hash: "",
  };
  errors = [];

  register() {
    this.errors = [];
    this.form.hash = this.hash;
    Api.auth.register(this.form).then((response) => {
      this.errors = response.data.errors || [];
      response.success && this.$router.push({ path: "/" });
    });
  }
}
</script>
