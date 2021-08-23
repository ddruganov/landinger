<template>
  <div
    class="color-picker rounded-circle border"
    :style="`background-color: ${modelValue}`"
    @click="show = true"
    @mouseleave="show = false"
    role="button"
  >
    <div v-if="show" class="colors rounded border">
      <div
        v-for="(color, i) in colors"
        :key="i"
        class="color rounded-circle"
        :style="`background-color: ${color}`"
        @click="pickColor(color)"
      >
        <i v-if="color === modelValue" class="color-selected fas fa-check" />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.color-picker {
  width: 20px;
  height: 20px;
  position: relative;
  z-index: 100 !important;
  .colors {
    position: absolute;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 5px;
    padding: 0.5rem;
    background: white;
    left: 10px;
    .color {
      width: 19px;
      height: 19px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 1px solid white;
      &:hover {
        border: none;
      }
      .color-selected {
        color: white;
      }
    }
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class ColorPickerComponent extends Vue {
  @Prop(String) readonly modelValue!: string;

  readonly colors = ["#000000", "#ff0000", "#00ff00", "#0000ff", "#ff00ff"];
  show = false;

  pickColor(value: string) {
    this.$emit("update:modelValue", value);
    this.$emit("change");
  }
}
</script>
