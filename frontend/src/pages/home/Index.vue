<template>
  <div class="block mt-5">
    <button class="button wfc" @click="() => createLanding()">Добавить лендинг</button>
  </div>

  <div class="landing-grid mt-3">
    <router-link :to="`/landing/${landing.id}/edit`" v-for="landing in landings" :key="landing.id" class="block">
      {{ landing.name }}
    </router-link>
  </div>
</template>

<script lang="ts">
import { CREATE_LANDING, landingStore } from "@/store/modules/landing.store";
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
}
</script>
