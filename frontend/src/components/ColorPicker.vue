<template>
  <div class="d-flex align-items-center">
    <div class="color-fields" @mousemove="(e) => placeDot(e)">
      <div
        class="color-field"
        :style="`background: linear-gradient(to right, white 0%, hsl(${hue}, 100%, 50%) 100%);`"
      />
      <div class="color-field" :style="`background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, black 100%);`" />
      <div class="dot" :style="`top: calc(${dotCoords.y}px - 0.5rem); left: calc(${dotCoords.x}px - 0.5rem)`" />
    </div>

    <div class="color-slider ml-5">
      <input class="input" type="range" min="0" max="360" v-model="hue" />
      <div class="handle" :style="`left: ${(hue / 360) * 100}%`" />
    </div>

    {{ hue }} {{ saturation }} {{ lightness }}
    <div class="output-color" :style="`background-color: hsl(${hue}, ${saturation}%, ${lightness}%)`" />
  </div>
</template>

<style lang="scss" scoped>
.color-fields {
  position: relative;
  width: 200px;
  height: 200px;
  .color-field {
    position: absolute;
    width: 100%;
    height: 100%;
  }
  .dot {
    border: 3px solid yellow;
    border-radius: 100%;
    width: 1rem;
    height: 1rem;
    position: absolute;
    pointer-events: none;
  }
}
.color-slider {
  width: 200px;
  height: 30px;
  transform-origin: 100px 15px;
  transform: rotate(-90deg);
  background: linear-gradient(to right, red 0%, #ff0 17%, lime 33%, cyan 50%, blue 66%, #f0f 83%, red 100%);
  position: relative;

  & > .input {
    width: 100%;
    height: 100%;
    appearance: none;
    opacity: 0;

    &::-webkit-slider-runnable-track {
      opacity: 0;
    }
    &::-moz-range-track {
      opacity: 0;
    }
    &::-ms-track {
      opacity: 0;
    }
  }
  & > .handle {
    position: absolute;
    height: 100%;
    width: 0.5rem;
    border: 1px solid #666;
    border-radius: 5px;
    top: 0;
    pointer-events: none;
  }
}

.output-color {
  width: 50px;
  height: 50px;
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class ColorPicker extends Vue {
  @Prop(String) modelValue!: string;

  private hue: number = 0;
  private saturation: number = 0;
  private lightness: number = 0;

  private dotCoords: { x: number; y: number } = {
    x: 0,
    y: 0,
  };

  private hexToDec(hex: string) {
    return parseInt(hex, 16);
  }

  private decToHex(dec: number) {
    return dec.toString(16);
  }

  private placeDot(e: PointerEvent) {
    if (!e.buttons || e.button !== 0) {
      return;
    }

    this.dotCoords = {
      x: e.offsetX,
      y: e.offsetY,
    };
    const target = e.target as HTMLDivElement;
    this.saturation = Math.round((this.dotCoords.x / target.getBoundingClientRect().width) * 100);
  }
}
</script>
