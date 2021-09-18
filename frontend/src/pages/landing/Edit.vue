<template>
  <div class="landing-edit-container" :style="`background: ${chosenBackground.value}`">
    <div class="block topbar">
      <go-back link="/" />
      <button class="button wfc" @click="() => save()">Сохранить</button>
    </div>

    <div class="block edit">
      <form-input v-model="landing.name" label="Название" />
      <button class="button fc" modal-trigger="chooseBackground">Выбрать фон</button>
    </div>

    <template v-if="landing">
      <div class="links">
        <div class="block create" @click="() => createLink()" role="button">
          <h5 class="header">Создать ссылку</h5>
          <i class="icon fas fa-plus my-auto" />
        </div>

        <draggable-list v-model="landing.links">
          <template #block="{item}">
            <form-input v-model="item.name" class="mb-3" />
            <form-input v-model="item.value" />
          </template>
        </draggable-list>
      </div>
    </template>
  </div>

  <modal-window id="chooseBackground" hideFooter>
    <template #title>Выберите фон лендинга</template>
    <template #body>
      <button
        v-for="background in backgrounds"
        :key="background.id"
        @click="() => chooseBackground(background.id)"
        class="button"
        :disabled="background.id === chosenBackground.id"
      >
        <i class="icon fas fa-check" />
        <span>{{ background.name }}</span>
        <div class="display" :style="`background: ${background.value}`" />
      </button>
    </template>
  </modal-window>
</template>

<script lang="ts">
import { CREATE_LANDING_LINK, DELETE_LANDING_LINK, landingStore, SAVE_LANDING } from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";
import FormInput from "@/components/form/FormInput.vue";
import FormGroup from "@/components/form/FormGroup.vue";
import GoBack from "@/components/GoBack.vue";
import DraggableList from "@/components/DraggableList.vue";
import ModalWindow from "@/components/ModalWindow.vue";

@Options({
  components: { FormInput, FormGroup, GoBack, DraggableList, ModalWindow },
})
export default class LandingEdit extends Vue {
  get id() {
    return Number(this.$router.currentRoute.value.params.id);
  }

  get landing() {
    return landingStore.context(this.$store).getters.landingById(this.id)!;
  }

  get backgrounds() {
    return landingStore.context(this.$store).getters.common.backgrounds;
  }

  get chosenBackground() {
    return this.backgrounds.find((bg: any) => bg.id === this.landing.backgroundId);
  }

  createLink() {
    landingStore.context(this.$store).dispatch(CREATE_LANDING_LINK, this.id);
  }

  deleteLink(id: number) {
    landingStore.context(this.$store).dispatch(DELETE_LANDING_LINK, id);
  }

  save() {
    landingStore.context(this.$store).dispatch(SAVE_LANDING, this.landing);
  }

  private chooseBackground(id: number) {
    this.landing.backgroundId = id;
  }
}
</script>
