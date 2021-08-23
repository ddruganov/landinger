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
    <div v-if="showFilter" class="filter rounded d-flex flex-column mt-3 p-3">
      <!-- NAME -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Имя:</span>
        <input class="form-control form-control-sm w-auto" v-model="filter.name" @input="applyFilter()" />
      </div>
      <!-- PRICE -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Цена от</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.price.from"
          style="max-width: 100px"
          @input="applyFilter()"
        />
        <span class="mx-3">до</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.price.to"
          style="max-width: 100px"
          @input="applyFilter()"
        />
      </div>
      <!-- VISIT COUNT -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Посещения от</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.hours.from"
          style="max-width: 100px"
          @input="applyFilter()"
        />
        <span class="mx-3">до</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.hours.to"
          style="max-width: 100px"
          @input="applyFilter()"
        />
      </div>
      <!-- EXPIRES IN -->
      <div class="field d-flex align-items-center mb-1">
        <span class="alias me-3">Длительность от</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.expiresIn.from"
          style="max-width: 100px"
          @input="applyFilter()"
        />
        <span class="mx-3">до</span>
        <input
          class="form-control form-control-sm"
          type="number"
          v-model="filter.expiresIn.to"
          style="max-width: 100px"
          @input="applyFilter()"
        />
      </div>
      <!-- ACTIVITY TYPE -->
      <div v-if="activities" class="field activity d-flex align-items-center mb-1">
        <span class="alias me-3">Вид активности</span>
        <select
          class="form-control form-control-sm"
          v-model="filter.activityId"
          style="max-width: 150px"
          @input="applyFilter(0)"
        >
          <option :value="0" :disabled="true">Выберите вид активности</option>
          <option :value="null">Любая</option>
          <option v-for="activity in activities" :key="activity.id" :value="activity.id">
            {{ activity.name }}
          </option>
        </select>
      </div>
      <!-- ACTIVE -->
      <div class="field d-flex align-items-center">
        <span class="alias me-3">Активен:</span>
        <custom-switch v-model="filter.active" @change="applyFilter(0)" :size="16" />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.filter {
  display: grid;
  .field {
    .alias {
      width: 130px;
      text-align: end;
    }
  }
}
</style>

<script lang="ts">
import { activityStore } from "@/store/modules/activity.store";
import { Options, Vue } from "vue-class-component";
import CustomSwitch from "@/components/CustomSwitch.vue";

@Options({
  components: { CustomSwitch },
})
export default class PassFilter extends Vue {
  get activities() {
    return activityStore.context(this.$store).state.activities;
  }
  defaultFilter = {
    name: "",
    price: {
      from: 0,
      to: 0,
    },
    hours: {
      from: 0,
      to: 0,
    },
    expiresIn: {
      from: 0,
      to: 0,
    },
    activityId: 0,
    active: null,
  };
  filter = {};
  timer = 0;
  showFilter = false;

  mounted() {
    this.resetFilter();
  }
  applyFilter(timeout = 500) {
    clearTimeout(this.timer);
    this.timer = setTimeout(() => {
      this.$emit("apply", this.filter);
    }, timeout);
  }
  resetFilter() {
    this.filter = Object.assign({}, this.defaultFilter);
    this.$emit("reset", this.filter);
  }
}
</script>
