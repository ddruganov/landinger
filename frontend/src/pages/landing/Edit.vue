<template>
  <div class="block mt-5">
    <h3>Редактирование лендинга</h3>
  </div>

  <div class="block mt-3">
    {{ landing }}
  </div>
</template>

<script lang="ts">
import { landingStore } from "@/store/modules/landing.store";
import { Vue } from "vue-class-component";

export default class LandingEdit extends Vue {
  get id() {
    return Number(this.$router.currentRoute.value.params.id);
  }

  get landing() {
    return landingStore.context(this.$store).getters.landingById(this.id);
  }
}
</script>
