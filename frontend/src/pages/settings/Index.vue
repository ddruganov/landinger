<template>
  <div class="user-settings container my-5">
    <div class="block column">
      <h3 class="mb-3">Настройки профиля</h3>

      <div class="main-settings">
        <image-upload showDeleteButton v-model="authenticatedUser.image" label="аватар" @change="() => save()" />
        <div class="info-inputs">
          <form-input class="mb-3" v-model="authenticatedUser.name" label="имя" disabled />
          <form-input class="mb-3" v-model="authenticatedUser.email" label="email" disabled />
        </div>
      </div>
    </div>

    <div class="block column payment-settings">
      <h3 class="mb-3">Услуги и оплата</h3>
      <div class="paid-services">
        <h5 class="text-center">Оплаченные услуги</h5>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Дата создания</th>
              <th scope="col">Дата окончания</th>
              <th scope="col">Услуга</th>
              <th scope="col">Стоимость</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="paidService in paidServices" :key="paidService.id">
              <th scope="row">{{ paidService.id }}</th>
              <td>{{ paidService.creationDate }}</td>
              <td>{{ paidService.expirationDate }}</td>
              <td>{{ paidService.serviceName }}</td>
              <td>{{ Number(paidService.pricePaid) }} ₽</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="allowed-services">
        <dropdown-menu>
          <template #caption>
            Добавить услугу
          </template>
          <template #content>
            <button
              class="button mb-3"
              v-for="allowedService in allowedServices"
              :key="allowedService.id"
              @click="() => createPaidService(allowedService.id)"
            >
              {{ allowedService.name }}
            </button>
          </template>
        </dropdown-menu>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Api from "@/common/api";
import DropdownMenu from "@/components/DropdownMenu.vue";
import FormInput from "@/components/form/FormInput.vue";
import ImageUpload from "@/components/ImageUpload.vue";
import { authStore } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";

type PaidService = {
  id: number;
  creationDate: string;
  expirationDate: string;
  serviceName: string;
  pricePaid: number;
};

type AllowedService = {
  id: number;
  name: string;
};

@Options({
  components: { FormInput, ImageUpload, DropdownMenu },
})
export default class SettingsIndex extends Vue {
  get authenticatedUser() {
    return authStore.context(this.$store).getters.authenticatedUser;
  }

  private allowedServices: AllowedService[] = [];
  private paidServices: PaidService[] = [];

  mounted() {
    Api.payment.service.paid
      .all()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.paidServices = response.data;
      })
      .catch(() => this.$notifications.error("Ошибка загрузки оплаченных услуг"));

    Api.payment.service
      .all()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.allowedServices = response.data;
      })
      .catch(() => this.$notifications.error("Ошибка загрузки доступных услуг"));
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
