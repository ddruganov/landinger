<template>
  <div class="payment-settings">
    <div class="paid-services">
      <h3>Оплаченные услуги</h3>
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
            <td>{{ paidService.expirationDate || "Не оплачено" }}</td>
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
            @click="() => displayDurationOptions(allowedService.id)"
          >
            {{ allowedService.name }}
          </button>
        </template>
      </dropdown-menu>
    </div>
  </div>

  <modal-window id="serviceDurations" hideFooter>
    <template #title>
      Выберите продолжительность услуги
    </template>
    <template #body>
      <div class="d-flex flex-column align-items-center justify-content-center">
        <button
          v-for="serviceDuration in serviceDurations"
          :key="serviceDuration.id"
          class="button d-flex flex-column mb-3"
          @click="() => createPaidService(serviceDuration.id)"
        >
          <span>{{ serviceDuration.duration }} дней</span>
          <span>{{ serviceDuration.price }} ₽</span>
        </button>
      </div>
    </template>
  </modal-window>
</template>

<script lang="ts">
import Api from "@/common/api";
import DropdownMenu from "@/components/DropdownMenu.vue";
import ModalWindow from "@/plugins/modal/ModalWindow.vue";
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

type ServiceDuration = {
  id: number;
  duration: number;
  price: number;
};

@Options({
  components: { DropdownMenu, ModalWindow },
})
export default class PaymentSettings extends Vue {
  private allowedServices: AllowedService[] = [];
  private paidServices: PaidService[] = [];
  private serviceDurations: ServiceDuration[] = [];

  mounted() {
    this.load();
  }

  private load() {
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

  displayDurationOptions(serviceId: number) {
    Api.payment.service.durations
      .all(serviceId)
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.serviceDurations = response.data;
        this.$modal.open("serviceDurations");
      })
      .catch(() => this.$notifications.error("Ошибка загрузки вариантов услуг"));
  }

  createPaidService(serviceDurationId: number) {
    Api.payment.service.paid
      .create(serviceDurationId)
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.load();
      })
      .catch(() => this.$notifications.error("Ошибка создания счёт для оплаты услуги"));
  }
}
</script>
