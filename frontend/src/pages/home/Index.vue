<template>
  <div class="landing-grid container">
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
      <corner-icon icon="fas fa-times" @click="() => deleteLanding(landing.id)" />
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
import CornerIcon from "@/components/CornerIcon.vue";
import { CREATE_LANDING, DELETE_LANDING, landingStore } from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { CornerIcon },
})
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
