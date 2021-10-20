<template>
  <div class="landing-grid container">
    <div class="card-container">
      <div class="view-card create-landing" @click="() => createLanding()">
        <i class="icon fas fa-plus my-auto" />
      </div>
    </div>

    <landing-card v-for="landing in landings" :key="landing.id" :landing="landing" />

    <image-upload v-model="image" />
  </div>
</template>

<style lang="scss" scoped>
.card-container {
  margin-right: 1rem;
  margin-bottom: 1rem;
}
</style>

<script lang="ts">
import { CREATE_LANDING, landingStore } from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";
import LandingCard from "@/components/LandingCard.vue";
import ImageUpload from "@/components/ImageUpload.vue";

@Options({
  components: { LandingCard, ImageUpload },
})
export default class HomeIndex extends Vue {
  private image = { id: null, url: null };

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
