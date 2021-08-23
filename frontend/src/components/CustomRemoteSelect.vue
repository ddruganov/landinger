<template>
  <custom-select :models="models" v-model:query="query" v-bind="$attrs">
    <template #beforeoptions>
      <div v-if="requestPending" class="requesting d-flex align-items-center">
        <span class="text-muted">Загрука</span>
        <div class="spinner-border spinner-border-sm text-primary ms-1" />
      </div>
      <div
        v-else-if="!delayingInput && query.length && !models.length"
        class="requesting text-muted d-flex align-items-center"
      >
        <span>По запросу</span> <span class="font-weight-bold mx-1">"{{ query }}"</span> <span>ничего не найдено</span>
      </div>
    </template>
  </custom-select>
</template>

<style lang="scss">
.requesting {
  height: 2rem;
  margin-left: 12px;
}
</style>

<script lang="ts">
import CustomSelect from "./CustomSelect.vue";
import Api from "@/common/api/index";
import { Options, Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";

type Model = { [key: string]: any };

@Options({
  components: { CustomSelect },
})
export default class CustomRemoteSelect extends Vue {
  @Prop({ type: String, required: true }) readonly modelName!: string;
  @Prop({ type: String, required: false, default: "name" }) readonly filterField!: string;
  @Prop({ type: Number, required: false, default: 500 }) readonly inputDelay!: number;

  models: Model[] = [];
  query = "";
  queryTimerId = 0;
  requestPending = false;
  delayingInput = false;

  @Watch("query") onQueryChange() {
    clearTimeout(this.queryTimerId);
    this.delayingInput = true;
    this.queryTimerId = setTimeout(this.loadModels, this.inputDelay);
  }

  loadModels() {
    this.delayingInput = false;
    if (!this.query) {
      return;
    }

    this.requestPending = true;
    Api.getModule(this.modelName)
      .autocomplete({ [this.filterField]: this.query })
      .then((response) => {
        if (response.success) {
          this.models = response.data.models;
        } else {
          this.$notifications.error("Ошибка загрузки");
        }
      })
      .finally(() => {
        this.requestPending = false;
      });
  }
}
</script>
