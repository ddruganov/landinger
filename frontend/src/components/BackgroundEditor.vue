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
        const svg =
          "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 100 84'%3E%3Cpath fill='%23017ac8' d='M90.2 0h-16a2 2 0 1 0 0 3.9h16c3.3 0 5.9 2.6 5.9 5.8V64L64.4 32.4a2 2 0 0 0-2.8 0L41 53l-4.9-5a2 2 0 0 0-2.8 0L4.5 76.8c-.4-.8-.6-1.6-.6-2.5V9.7A5.8 5.8 0 0 1 9.8 4h49.6a2 2 0 1 0 0-3.9H9.8A9.8 9.8 0 0 0 0 9.7v64.6a9.7 9.7 0 0 0 3 7A9.8 9.8 0 0 0 9.8 84h80.4c5.4 0 9.8-4.4 9.8-9.7V9.7c0-5.3-4.4-9.7-9.8-9.7zm0 80.1H9.8c-1 0-1.8-.2-2.6-.6l27.5-27.3 5 4.9L52 69.4c.8.8 2 .8 2.8 0 .8-.7.8-2 0-2.7l-11-11L63 36.6l33 32.8v4.9c0 3.2-2.5 5.8-5.8 5.8z'/%3E%3C/svg%3E";
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
