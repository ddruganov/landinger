<template>
  <template v-if="dataLoaded">
    <topbar />
    <div class="container">
      <router-view />
    </div>
    <Footer />
  </template>
</template>

<style lang="scss">
.basic-layout {
  min-height: calc(100vh - 71px);
  width: 100%;
  .content-container {
    flex: 1;
    .content {
      flex: 1;
    }
  }
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.5s ease;
}
.slide-enter-active {
  animation: slide-in 0.5s;
}
.slide-leave-active {
  animation: slide-in 0.5s reverse;
}
@keyframes slide-in {
  0% {
    transform: translateX(-250px);
  }
  100% {
    transform: translateX(0px);
  }
}
</style>

<script lang="ts">
import Topbar from "./Topbar.vue";
import Footer from "./Footer.vue";

import { authStore, GET_CURRENT_USER } from "@/store/modules/auth.store";
import { Options, Vue } from "vue-class-component";
import { landingStore, LOAD_ALL_LANDINGS } from "@/store/modules/landing.store";

@Options({
  components: { Topbar, Footer },
})
export default class MainLayout extends Vue {
  dataLoaded = false;

  async mounted() {
    await this.load();
  }
  async load() {
    await authStore.context(this.$store).dispatch(GET_CURRENT_USER);
    await landingStore.context(this.$store).dispatch(LOAD_ALL_LANDINGS);
    this.dataLoaded = true;
  }
}
</script>
