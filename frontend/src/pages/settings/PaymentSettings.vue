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
            <td>{{ paidService.expirationDate }}</td>
            <td>{{ paidService.serviceName }}</td>
            <td>{{ Number(paidService.pricePaid) }} ₽</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="invoices-to-pay">
      <h3>Счета на оплату</h3>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Дата создания</th>
            <th scope="col">Дата оплаты</th>
            <th scope="col">Услуга</th>
            <th scope="col">Сумма</th>
            <th scope="col">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices" :key="invoice.id">
            <th scope="row">{{ invoice.id }}</th>
            <td>{{ invoice.creationDate }}</td>
            <td>{{ invoice.paymentDate || "Не оплачено" }}</td>
            <td>{{ invoice.serviceName }}</td>
            <td>{{ Number(invoice.paymentAmount) }} ₽</td>
            <td>
              <button v-if="!invoice.paymentDate" class="button">Оплатить</button>
            </td>
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
            class="button"
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
      <button
        v-for="serviceDuration in serviceDurations"
        :key="serviceDuration.id"
        class="button"
        @click="() => createPaidService(serviceDuration.id)"
      >
        <span class="price">{{ serviceDuration.price }} ₽</span>
        <span class="divider">/</span>
        <span class="duration">{{ serviceDuration.duration }} дней</span>
      </button>
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

type Invoice = {
  id: number;
  creationDate: string;
  paymentDate: string;
  paymentAmount: number;
};

@Options({
  components: { DropdownMenu, ModalWindow },
})
export default class PaymentSettings extends Vue {
  private allowedServices: AllowedService[] = [];
  private paidServices: PaidService[] = [];
  private serviceDurations: ServiceDuration[] = [];
  private invoices: Invoice[] = [];

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

    Api.payment.invoice
      .all()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.exception);
        }

        this.invoices = response.data;
      })
      .catch(() => this.$notifications.error("Ошибка загрузки счетов на оплату"));
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
