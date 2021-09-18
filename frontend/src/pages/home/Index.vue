<template>
  <div class="landing-grid">
    <div class="card-container">
      <div class="view-card create-landing" @click="() => createLanding()">
        <h5 class="header">Новый лендинг</h5>
        <i class="icon fas fa-plus my-auto" />
      </div>
    </div>

    <div class="card-container" v-for="landing in landings" :key="landing.id">
      <router-link class="view-card" :to="`/landing/${landing.id}/edit`">
        <h5 class="header">{{ landing.name }}</h5>
        <i class="icon fas fa-pen my-auto" />
      </router-link>
      <i class="icon corner delete fas fa-times" @click="() => deleteLanding(landing.id)" />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.card-container {
  margin-right: 1rem;
  margin-bottom: 1rem;
}
</style>

<script lang="ts">
import { CREATE_LANDING, DELETE_LANDING, landingStore } from "@/store/modules/landing.store";
import { Vue } from "vue-class-component";

export default class HomeIndex extends Vue {
  get landings() {
    return landingStore.context(this.$store).getters.landings;
  }

  createLanding() {
    landingStore
      .context(this.$store)
      .dispatch(CREATE_LANDING)
      .then((newLandingId) => {
        newLandingId && this.$router.push({ path: `/landing/${newLandingId}/edit` });
      });
  }

  deleteLanding(id: number) {
    this.$prompt("Вы уверены, что хотите удалить лендинг? Это удалить все данные, связанные с ним").then((result) => {
      result === 1 && landingStore.context(this.$store).dispatch(DELETE_LANDING, id);
    });
  }
}
</script>
