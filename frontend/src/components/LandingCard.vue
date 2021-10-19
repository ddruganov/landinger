<template>
  <div class="landing-card card-container">
    <h5 class="header">{{ landing.name }}</h5>
    <div class="controls">
      <router-link class="button secondary fc" :to="`/landing/${landing.id}/edit`">
        <i class="icon fas fa-pen my-auto" />
      </router-link>
      <a class="button secondary fc" :href="landing.link" target="_blank">
        <i class="fas fa-external-link-alt" />
      </a>
      <router-link class="button secondary fc" :to="`/landing/${landing.id}/stats`">
        <i class="fas fa-chart-bar" />
      </router-link>
      <button class="button secondary fc" @click="() => deleteLanding(landing.id)">
        <i class="far fa-trash-alt" />
      </button>
    </div>
    <!-- <corner-icon icon="fas fa-times"  /> -->
  </div>
</template>

<script lang="ts">
import Landing from "@/types/landing/Landing";
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import CornerIcon from "@/components/CornerIcon.vue";
import { DELETE_LANDING, landingStore } from "@/store/modules/landing.store";

@Options({
  components: { CornerIcon },
})
export default class LandingCard extends Vue {
  @Prop(Object) readonly landing!: Landing;

  deleteLanding(id: number) {
    this.$prompt("Вы уверены, что хотите удалить лендинг?<br>Это удалит все данные, связанные с ним").then((result) => {
      result === 1 && landingStore.context(this.$store).dispatch(DELETE_LANDING, id);
    });
  }
}
</script>
