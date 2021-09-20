<template>
  <template v-if="landing">
    <div
      class="landing-edit-container"
      :key="reloadPageKey"
      :style="`background: ${newBackground || landing.background}`"
      style="background-size: cover"
    >
      <div class="block topbar">
        <go-back link="/" />
        <button class="button wfc" @click="() => save()">Сохранить</button>
      </div>

      <div class="block edit">
        <form-input v-model="landing.name" label="Название" />
        <button class="button fc" modal-trigger="chooseBackground">Выбрать фон</button>
      </div>

      <div class="links">
        <div class="block create" @click="() => createLink()" role="button">
          <h5 class="header">Создать ссылку</h5>
          <i class="icon fas fa-plus my-auto" />
        </div>

        <draggable-list v-model="landing.links">
          <template #block="{item}">
            <form-input v-model="item.name" class="mb-3" />
            <form-input v-model="item.value" />
            <corner-icon icon="far fa-trash-alt" @click="() => deleteLink(item.id)" />
          </template>
        </draggable-list>
      </div>
    </div>

    <modal-window id="chooseBackground" @show="() => (newBackground = landing.background)">
      <template #title>Выберите фон лендинга</template>
      <template #body>
        <tabs :items="backgroundTypes" @switch="(item) => onTabSwitch(item)">
          <template #tab0="{item}">
            <div class="p-3 mt-3">
              <input type="color" v-model="newBackground" />
            </div>
          </template>
          <template #tab1="{item}"
            ><div class="p-3 mt-3">
              {{ item }}
              tab 2
            </div></template
          >
          <template #tab2="{item}"
            ><div class="p-3 mt-3">
              {{ item }}
              tab 3
            </div></template
          >
        </tabs>
      </template>
      <template #footer>
        <button class="button white wfc" @click="() => resetBackground()">Сбросить</button>
        <button class="button wfc" @click="() => applyBackground()">Применить</button>
      </template>
    </modal-window>
  </template>
</template>

<script lang="ts">
import { CREATE_LANDING_LINK, DELETE_LANDING_LINK, landingStore, SAVE_LANDING } from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";
import FormInput from "@/components/form/FormInput.vue";
import FormGroup from "@/components/form/FormGroup.vue";
import GoBack from "@/components/GoBack.vue";
import DraggableList from "@/components/DraggableList.vue";
import ModalWindow from "@/components/ModalWindow.vue";
import CornerIcon from "@/components/CornerIcon.vue";
import Tabs from "@/components/Tabs.vue";
import LandingBackgroundType from "@/types/landing/LandingBackgroundType";

@Options({
  components: { FormInput, FormGroup, GoBack, DraggableList, ModalWindow, CornerIcon, Tabs },
})
export default class LandingEdit extends Vue {
  private newBackground: string = "";

  private reloadPageKey: number = 0;

  get id() {
    return Number(this.$router.currentRoute.value.params.id);
  }

  get landing() {
    return landingStore.context(this.$store).getters.landingById(this.id)!;
  }

  createLink() {
    landingStore.context(this.$store).dispatch(CREATE_LANDING_LINK, this.id);
  }

  deleteLink(id: number) {
    landingStore
      .context(this.$store)
      .dispatch(DELETE_LANDING_LINK, { landingId: this.landing.id, id: id })
      .then((success) => {
        success && this.reloadPageKey++;
      });
  }

  save() {
    landingStore.context(this.$store).dispatch(SAVE_LANDING, this.landing);
  }

  private applyBackground() {
    this.landing.background = this.newBackground;
    this.resetBackground();
  }

  private resetBackground() {
    this.newBackground = this.landing.background;
  }

  get backgroundTypes() {
    return landingStore.context(this.$store).getters.common.background.types;
  }

  private onTabSwitch(tabItem: LandingBackgroundType) {
    this.newBackground = tabItem.default;
  }
}
</script>
