<template>
  <div class="color-picker">
    <div class="color-fields" @click="(e) => placeDot(e)" @mousemove="(e) => placeDot(e)">
      <div
        class="color-field"
        :style="`background: linear-gradient(to right, white 0%, hsl(${hue}, 100%, 50%) 100%);`"
      />
      <div class="color-field" :style="`background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, black 100%);`" />
      <div class="dot" :style="`top: calc(${dotCoords.y}px - 0.5rem); left: calc(${dotCoords.x}px - 0.5rem)`" />
    </div>

    <div class="color-slider" @click="(e) => placeHandle(e)" @mousemove="(e) => placeHandle(e)">
      <div class="handle" :style="`top: calc(${(hue / 360) * 100}% - 0.25rem)`" />
    </div>

    <div class="output-color" :style="`background-color: hsl(${hue}, ${saturation}%, ${luminocity}%)`" />
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";

export default class ColorPicker extends Vue {
  @Prop(String) modelValue!: string;

  @Watch("hue") onHueChanged() {
    this.emitUpdate();
  }

  private hue: number = 0;
  private saturation: number = 0;
  private luminocity: number = 0;

  mounted() {
    // parse modelValue hsl and set initial values
    const values = this.modelValue
      .replaceAll("%", "")
      .replace("hsl(", "")
      .replace(")", "")
      .split(",")
      .map((val) => Number(val.trim()));
    this.hue = values[0];
    this.saturation = values[1];
    this.luminocity = values[2];

    this.dotCoords.x = this.saturation;
  }

  private dotCoords: { x: number; y: number } = {
    x: 0,
    y: 0,
  };

  private placeDot(e: PointerEvent) {
    if ((e.type === "mousemove" && !e.buttons) || e.button !== 0) {
      return;
    }

    this.dotCoords = {
      x: e.offsetX,
      y: e.offsetY,
    };

    const target = e.target as HTMLDivElement;

    const boundingRect = target.getBoundingClientRect();
    const xNormalized = this.dotCoords.x / boundingRect.width;
    const yNormalized = this.dotCoords.y / boundingRect.height;

    this.saturation = Math.round(xNormalized * 100);

    const leftRange = 100 - Math.round(yNormalized * 100);
    const rightRange = leftRange / 2;
    this.luminocity = Math.round(leftRange - xNormalized * rightRange);

    this.emitUpdate();
  }

  private placeHandle(e: PointerEvent) {
    if ((e.type === "mousemove" && !e.buttons) || e.button !== 0) {
      return;
    }

    const target = e.target as HTMLDivElement;

    const boundingRect = target.getBoundingClientRect();
    const yNormalized = e.offsetY / boundingRect.height;

    this.hue = yNormalized * 360;

    this.emitUpdate();
  }

  private emitUpdate() {
    const outputValue = `hsl(${this.hue}, ${this.saturation}%, ${this.luminocity}%)`;
    this.$emit("update:modelValue", outputValue);
    this.$emit("change", outputValue);
  }
}
</script>
