<template>
  <div class="landing-edit-container" :style="`background: ${landing.background.value}`" :key="reloadKey">
    <div class="block topbar">
      <go-back link="/" />
      <button class="button wfc" @click="() => save()">Сохранить</button>
    </div>

    <div class="block edit">
      <form-input v-model="landing.name" label="Название" />
      <button class="button fc" modal-trigger="chooseBackground">Выбрать фон</button>
    </div>

    <div class="block controls">
      <button class="button create fc" @click="() => createEntity(5)">
        <i class="icon fas fa-plus" />
        <div class="header">Создать ссылку</div>
      </button>
      <button class="button create fc" @click="() => createEntity(4)">
        <i class="icon fas fa-plus" />
        <div class="header">Создать группу</div>
      </button>
    </div>

    <div class="entities">
      <draggable-list v-model="landing.entities">
        <template #block="{item}">
          <form-input v-model="item.name" class="mb-3" />
          <form-input v-model="item.value" />
          <corner-icon icon="far fa-trash-alt" @click="() => deleteLink(item.id)" />
        </template>
      </draggable-list>
    </div>
  </div>

  <background-editor v-model="landing.background" @change="++reloadKey" />
</template>

<script lang="ts">
import {
  CREATE_LANDING_ENTITY,
  DELETE_LANDING_ENTITY,
  landingStore,
  SAVE_LANDING,
} from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";
import FormInput from "@/components/form/FormInput.vue";
import FormGroup from "@/components/form/FormGroup.vue";
import GoBack from "@/components/GoBack.vue";
import DraggableList from "@/components/DraggableList.vue";
import CornerIcon from "@/components/CornerIcon.vue";
import BackgroundEditor from "@/components/BackgroundEditor.vue";

@Options({
  components: { FormInput, FormGroup, GoBack, DraggableList, CornerIcon, BackgroundEditor },
})
export default class LandingEdit extends Vue {
  private reloadKey: number = 0;

  get id() {
    return Number(this.$router.currentRoute.value.params.id);
  }

  get landing() {
    return landingStore.context(this.$store).getters.landingById(this.id)!;
  }

  createEntity(modelTypeId: number, parentId: number | undefined) {
    landingStore
      .context(this.$store)
      .dispatch(CREATE_LANDING_ENTITY, { landingId: this.id, modelTypeId: modelTypeId, parentId: parentId });
  }

  deleteLink(id: number) {
    landingStore
      .context(this.$store)
      .dispatch(DELETE_LANDING_ENTITY, { landingId: this.landing.id, id: id })
      .then((success) => {
        success && ++this.reloadKey;
      });
  }

  save() {
    landingStore.context(this.$store).dispatch(SAVE_LANDING, this.landing);
  }
}
</script>
