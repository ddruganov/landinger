<template>
  <div class="color-picker">
    <div class="top-row">
      <div class="color-fields" @click="(e) => placeDot(e)" @mousemove="(e) => placeDot(e)">
        <div
          class="color-field"
          :style="`background: linear-gradient(to right, white 0%, hsl(${hsl.h}, 100%, 50%) 100%);`"
        />
        <div class="color-field" :style="`background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, black 100%);`" />
        <div class="dot" :style="`top: calc(${dotCoords.y}% - 0.5rem); left: calc(${dotCoords.x}% - 0.5rem)`" />
      </div>

      <div class="color-slider" @click="(e) => placeHandle(e)" @mousemove="(e) => placeHandle(e)">
        <div class="handle" :style="`top: calc(${(hsl.h / 360) * 100}% - 0.25rem)`" />
      </div>
    </div>
    <div class="rgb-inputs">
      <form-input v-model="rgb.r" label="R" @change="() => handleRGBChange()" />
      <form-input v-model="rgb.g" label="G" @change="() => handleRGBChange()" />
      <form-input v-model="rgb.b" label="B" @change="() => handleRGBChange()" />
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import FormInput from "./form/FormInput.vue";
import RGB from "@/types/color/RGB";
import HSL from "@/types/color/HSL";
import { hslToRgb, rgbToHsl } from "@/common/service/color.helper";

@Options({
  components: { FormInput },
})
export default class ColorPicker extends Vue {
  @Prop(String) modelValue!: string;

  private rgb: RGB = {
    r: 0,
    g: 0,
    b: 0,
  };

  private hsl: HSL = {
    h: 0,
    s: 0,
    l: 0,
  };

  mounted() {
    this.parseModelValue();
  }

  private parseModelValue() {
    // parse modelValue hsl and set initial values
    // most likely not the best way to do so
    const values = this.modelValue.split(",").map((val) => Number(val));
    this.setHSL({
      h: values[0],
      s: values[1],
      l: values[2],
    });
  }

  private setHSL(newValue: HSL) {
    this.hsl = newValue;

    const xNormalized = this.hsl.s / 100;
    // not checking for xNormalized being 2 because it cant be more than one; the name implies it
    const yNormalized = (50 * xNormalized + this.hsl.l - 100) / (50 * (xNormalized - 2));

    this.dotCoords.x = xNormalized * 100;
    this.dotCoords.y = yNormalized * 100;
    this.rgb = hslToRgb(this.hsl);
  }

  private dotCoords: { x: number; y: number } = {
    x: 0,
    y: 0,
  };

  private placeDot(e: PointerEvent) {
    if (e.type === "mousemove" && (!e.buttons || e.button !== 0)) {
      return;
    }

    const target = e.target as HTMLDivElement;

    const boundingRect = target.getBoundingClientRect();
    const xNormalized = e.offsetX / boundingRect.width;
    const yNormalized = e.offsetY / boundingRect.height;

    this.dotCoords = {
      x: xNormalized * 100,
      y: yNormalized * 100,
    };

    this.hsl.s = Math.round(xNormalized * 100);

    const leftRange = 100 - Math.round(yNormalized * 100);
    const rightRange = leftRange / 2;
    this.hsl.l = Math.round(leftRange - xNormalized * rightRange);

    this.rgb = hslToRgb(this.hsl);
    this.emitUpdate();
  }

  private placeHandle(e: PointerEvent) {
    if (e.type === "mousemove" && (!e.buttons || e.button !== 0)) {
      return;
    }

    const target = e.target as HTMLDivElement;

    const boundingRect = target.getBoundingClientRect();
    const yNormalized = Math.max(e.offsetY / boundingRect.height, 0);

    this.hsl.h = yNormalized * 360;

    this.rgb = hslToRgb(this.hsl);
    this.emitUpdate();
  }

  private emitUpdate() {
    const outputValue = Object.values(this.hsl).join(",");
    this.$emit("update:modelValue", outputValue);
    this.$emit("pick", outputValue);
  }

  private handleRGBChange() {
    this.setHSL(rgbToHsl(this.rgb));

    this.emitUpdate();
  }
}
</script>
