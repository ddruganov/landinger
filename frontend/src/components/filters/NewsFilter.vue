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
      <!-- EXPIRES IN -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Период с</span>
        <input
          class="form-control form-control-sm w-auto"
          type="date"
          v-model="filter.creationDate.from"
          @input="applyFilter"
        />
        <span class="mx-3">до</span>
        <input
          class="form-control form-control-sm w-auto"
          type="date"
          v-model="filter.expirationDate.to"
          @input="applyFilter"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.field {
  .alias {
    width: fit-content;
    text-align: end;
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";

export default class NewsFilter extends Vue {
  defaultFilter = {
    creationDate: {
      from: null,
    },
    expirationDate: {
      to: null,
    },
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
    this.filter = JSON.parse(JSON.stringify(this.defaultFilter));
    this.$emit("reset", this.filter);
  }
}
</script>
