<template>
  <!-- FILTER -->
  <div v-if="filter" class="d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <h5 class="m-0 p-0 me-3">Фильтр</h5>
        <button class="btn btn-primary btn-sm" @click="resetFilter()">
          <i class="fas fa-times" />
        </button>
      </div>
      <button class="btn text-dark btn-sm border rounded" @click="showFilter = !showFilter">
        <i v-if="showFilter" class="fas fa-minus" />
        <i v-else class="fas fa-plus" />
      </button>
    </div>
    <div v-if="showFilter" class="rounded d-flex flex-column mt-3 p-3">
      <!-- NAME -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Имя:</span>
        <input class="form-control form-control-sm w-auto" v-model="filter.name" @input="applyFilter" />
      </div>
      <!-- PHONE -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Телефон:</span>
        <input class="form-control form-control-sm w-auto" v-model="filter.email" @input="applyFilter" />
      </div>
      <!-- PAYRATE -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Ставка:</span>
        <input class="form-control form-control-sm w-auto" v-model="filter.payRate" @input="applyFilter" />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.field {
  .alias {
    width: 70px;
    text-align: end;
  }
}
</style>

<script lang="ts">
import { activityStore } from "@/store/modules/activity.store";
import { Vue } from "vue-class-component";

export default class CoachFilter extends Vue {
  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  defaultFilter = {
    name: null,
    email: null,
    payRate: null,
  };
  filter = {};
  timer = 0;
  showFilter = false;

  mounted() {
    this.resetFilter();
  }
  applyFilter() {
    clearTimeout(this.timer);
    this.timer = setTimeout(() => {
      this.$emit("apply", this.filter);
    }, 1000);
  }
  resetFilter() {
    this.filter = Object.assign({}, this.defaultFilter);
    this.$emit("reset", this.filter);
  }
}
</script>
