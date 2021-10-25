<template>
  <div class="user-settings container my-5">
    <div class="block column">
      <tabs :items="tabs" startValue="main">
        <template #mainTab>
          <main-settings />
        </template>
        <template #paymentTab>
          <payment-settings />
        </template>
      </tabs>
    </div>
  </div>
</template>

<script lang="ts">
import Tabs from "@/components/Tabs.vue";
import Tab from "@/types/tabs/Tab";
import { Options, Vue } from "vue-class-component";
import MainSettings from "./MainSettings.vue";
import PaymentSettings from "./PaymentSettings.vue";

@Options({
  components: { Tabs, MainSettings, PaymentSettings },
})
export default class SettingsIndex extends Vue {
  private get tabs(): Tab[] {
    return [
      {
        id: "main",
        name: "Основные",
      },
      {
        id: "payment",
        name: "Услуги и оплата",
      },
    ];
  }
}
</script>
