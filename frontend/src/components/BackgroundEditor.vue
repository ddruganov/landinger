<template>
  <modal-window
    id="chooseBackground"
    class="background-editor"
    @show="() => resetBackground()"
    @close="(graceful) => !graceful && resetBackground()"
  >
    <template #title>Выберите фон лендинга</template>
    <template #body>
      <tabs :items="tabs" :startValue="startTab" @switch="(tab) => onTabSwitch(tab)">
        <template #colorTab>
          <div class="tab-content color-tab">
            <div class="start-color">
              <div class="fw-bold mb-3">Начальный цвет</div>
              <color-picker class="mb-3" v-model="gradient[0]" @pick="(hsl) => handleGradientChange(0, hsl)" />
            </div>

            <div class="controls">
              <button
                class="button wfc copy-down"
                title="Копировать начальный цвет в конечный"
                @click="() => copyColors('down')"
              >
                <i class="icon fas fa-long-arrow-alt-down" />
              </button>
              <button class="button wfc swap" title="Поменять цвета местами" @click="() => copyColors('swap')">
                <i class="icon fas fa-arrows-alt-v" />
              </button>
              <button
                class="button wfc copy-up"
                title="Копировать конечный цвет в начальный"
                @click="() => copyColors('up')"
              >
                <i class="icon fas fa-long-arrow-alt-up" />
              </button>
            </div>

            <div class="end-color">
              <div class="fw-bold mb-3">Конечный цвет</div>
              <color-picker class="mb-3" v-model="gradient[1]" @pick="(hsl) => handleGradientChange(1, hsl)" />
            </div>
          </div>
        </template>
        <template #imageTab>
          <div class="p-3 mt-3 w-100">
            <form-input
              v-model="background.params"
              @input="(link) => handleImageChange(link)"
              label="Ссылка на изображение"
            />
          </div>
        </template>
      </tabs>
    </template>
    <template #footer>
      <button class="button white wfc" @click="() => resetBackground()">Сбросить</button>
      <button class="button wfc" @click="() => applyBackground()">Применить</button>
    </template>
  </modal-window>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import Tabs from "@/components/Tabs.vue";
import ColorPicker from "@/components/ColorPicker.vue";
import ModalWindow from "@/components/ModalWindow.vue";
import { Prop } from "vue-property-decorator";
import LandingBackground from "@/types/landing/LandingBackground";
import Tab from "@/types/tabs/Tab";
import FormInput from "./form/FormInput.vue";

@Options({
  components: { ColorPicker, Tabs, ModalWindow, FormInput },
})
export default class BackgroundEditor extends Vue {
  @Prop(Object) readonly modelValue!: LandingBackground;

  private get startTab() {
    return this.originalValueProxy.value.startsWith("linear-gradient") ? "color" : "image";
  }

  private get tabs(): Tab[] {
    return [
      {
        id: "color",
        name: "Цвет",
      },
      {
        id: "image",
        name: "Изображение",
      },
    ];
  }

  private get gradient() {
    return this.background.params.split(";");
  }

  private originalValueProxy!: LandingBackground;
  private background!: LandingBackground;

  mounted() {
    this.originalValueProxy = JSON.parse(JSON.stringify(this.modelValue));
  }

  private applyBackground() {
    window.dispatchEvent(new CustomEvent("modal.chooseBackground.close"));
  }

  private resetBackground() {
    this.background = this.originalValueProxy;
    this.$forceUpdate();
    this.emitChange();
  }

  private handleGradientChange(paramIndex: number, hsl: string) {
    const split = this.background.params.split(";");
    split[paramIndex] = hsl;
    this.background.params = split.join(";");

    const [h1, s1, l1] = split[0].split(",");
    const [h2, s2, l2] = split[1].split(",");

    this.background.value = `linear-gradient(hsl(${h1}, ${s1}%, ${l1}%) 0%, hsl(${h2}, ${s2}%, ${l2}%) 100%)`;

    this.emitChange();
  }

  private handleImageChange(link: string) {
    this.background = {
      value: `url(${link})`,
      params: link,
    };

    this.emitChange();
  }

  private emitChange() {
    this.$emit("update:modelValue", this.background);
    this.$emit("change", this.background);
  }

  private onTabSwitch(tab: Tab) {
    if (tab.id === this.startTab) {
      this.resetBackground();
      return;
    }

    switch (tab.id) {
      case "color":
        this.background = {
          value: "linear-gradient(hsl(204, 99%, 39.4%) 0%, hsl(0, 0%, 0%) 100%)",
          params: "204,99,39.4;0,0,0",
        };
        this.handleGradientChange(1, "0,0,0");
        break;
      case "image": {
        const svg = "http://localhost:8007/images/default.svg";
        this.background = {
          value: `url("${svg}")`,
          params: svg,
        };
        break;
      }
    }

    this.emitChange();
  }

  private copyColors(direction: string) {
    switch (direction) {
      case "down":
        this.handleGradientChange(1, this.background.params.split(";")[0]);
        break;
      case "swap": {
        const [topColor, bottomColor] = this.background.params.split(";");
        this.handleGradientChange(0, bottomColor);
        this.handleGradientChange(1, topColor);
        break;
      }
      case "up":
        this.handleGradientChange(0, this.background.params.split(";")[1]);
        break;
    }
  }
}
</script>
