<template>
  <div :id="id" class="autocomplete d-flex flex-column position-relative">
    <div
      class="d-flex flex-wrap search-field border rounded"
      :style="showDropdown && showInput ? 'border-radius: 5px 5px 0px 0px !important;' : ''"
      @click="dropdown(true)"
      role="button"
    >
      <div v-if="!selectedModels.length" class="placeholder text-muted">
        {{ promptText }}
      </div>
      <div
        v-for="selectedModel in selectedModels"
        :key="selectedModel[idFieldAlias]"
        class="selected border rounded m-1 px-1"
      >
        <span class="pe-1">{{ selectedModel[textFieldAlias] }}</span>
        <i class="remove fas fa-times rounded m-auto" @click="removeSelected(selectedModel)" role="button" />
      </div>
    </div>
    <div v-show="showDropdown && showInput" class="options w-100 border border-top-0 m-0 p-0 bg-white">
      <input
        v-model="searchQuery"
        @input="search()"
        class="search form-control form-control-sm"
        placeholder="Начните вводить..."
      />
      <slot name="beforeoptions" />
      <div
        v-for="model in suitableModels.filter((m) => !selectedModels.includes(m))"
        :key="model[idFieldAlias]"
        @mousedown="choose(model[idFieldAlias])"
        class="option d-flex ps-3 rounded-0"
      >
        <span class="my-auto">{{ model[textFieldAlias] }}</span>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.placeholder {
  margin: auto 0px;
  margin-left: 12px;
}

$element-height: 2rem;

.search-field {
  min-height: $element-height;
  font-size: 0.875rem;
  input {
    z-index: 0 !important;
    min-width: 140px;
    border: 0;
    &:focus {
      outline: none;
    }
  }
}

.selected {
  display: flex;
  align-items: center;
  background-color: #eee;
  cursor: default;
  user-select: none !important;
  .remove {
    color: #aaa !important;
    &:hover {
      color: #666 !important;
    }
  }
}

.options {
  z-index: 9999 !important;
  position: absolute;
  overflow: scroll !important;
  top: 100%;
  border-radius: 0px 0px 5px 5px;
  max-height: $element-height * 4.5; // 3.5 items
  .search {
    width: calc(100% - 0.5rem);
    margin: 0.25rem;
    text-indent: 2px;
  }
  .option {
    height: $element-height;
    &:hover {
      background-color: lightgray;
    }
    &:active {
      background-color: gray;
    }
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";

type Model = {
  [key: string]: any;
};

export default class CustomSelect extends Vue {
  @Prop(Array) readonly modelValue!: Model[];

  @Prop(Array) readonly models!: Model[];
  @Prop({ type: Number, default: 100 }) readonly maxModelCount!: number;
  @Prop({ type: String, default: "name" }) readonly textFieldAlias!: string;
  @Prop({ type: String, default: "id" }) readonly idFieldAlias!: string;
  @Prop({ type: String, default: "Выберите..." }) readonly promptText!: string;
  @Prop(String) readonly query!: string;

  @Watch("modelValue") onValueChanged() {
    this.selectedModels = this.modelValue || [];
  }
  @Watch("models") onModelsChanged() {
    this.load();
    this.search();
  }

  id = "CustomSelect_" + Date.now();
  selectedModels: Model[] = this.modelValue || [];
  suitableModels: Model[] = [];
  showDropdown = false;
  showInput = true;
  searchQuery = "";

  get searchInput(): HTMLInputElement {
    return document.getElementById(this.id)?.querySelector(".search")! as HTMLInputElement;
  }

  mounted() {
    this.searchQuery = this.query;

    this.load();

    document.addEventListener("click", (e) => {
      this.showDropdown = this.$el.contains(e.target);
    });
  }
  load() {
    this.suitableModels = this.models;
    this.showInput = this.selectedModels.length !== this.maxModelCount;
  }
  dropdown(value: boolean) {
    this.showDropdown = value;
    this.showDropdown &&
      this.$nextTick(() => {
        this.searchInput.focus();
      });
  }
  search() {
    if (!this.models) {
      return;
    }

    this.suitableModels = this.searchQuery
      ? this.models.filter((m) => m[this.textFieldAlias].toLowerCase().includes(this.searchQuery.toLowerCase()))
      : this.models;

    this.$emit("update:query", this.searchQuery);
  }
  choose(id: number) {
    const suitableModel = this.suitableModels.find((m) => m[this.idFieldAlias] === id);
    if (!suitableModel) {
      return;
    }

    this.selectedModels.push(suitableModel);
    this.onSelectedModelsChanged();
  }
  removeSelected(model: Model) {
    const index = this.selectedModels.indexOf(model);
    this.selectedModels.splice(index, 1);
    this.onSelectedModelsChanged();
  }
  onSelectedModelsChanged() {
    this.$emit("update:modelValue", this.selectedModels);
    this.showInput = this.selectedModels.length < this.maxModelCount;
  }
}
</script>
