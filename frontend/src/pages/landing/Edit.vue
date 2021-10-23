<template>
  <div class="landing-edit-container" :style="`background: ${landing.background.value}`" :key="reloadKey">
    <div class="container">
      <div class="block topbar">
        <go-back link="/" />
        <button class="button wfc" @click="() => save()">Сохранить</button>
      </div>
      <div class="settings block column mb-3">
        <div class="main-settings d-flex w-100">
          <image-upload showDeleteButton v-model="landing.image" label="Логотип" />
          <div class="d-flex flex-column ms-3 w-100">
            <form-input class="mb-3" v-model="landing.name" label="Название" />
            <form-input class="mb-3" v-model="landing.alias" label="Алиас" prefix="linktome.site/" />
            <button class="button" modal-trigger="chooseBackground">Выбрать фон</button>
          </div>
        </div>
      </div>
      <button class="button secondary smooth mb-2" modal-trigger="addEntity">
        <i class="icon fas fa-plus" style="font-size: 2rem" />
      </button>
      <div class="entities">
        <tree v-model="landing.entities" @change="++reloadKey">
          <template #fold="{value, click}">
            <i
              class="link-icon"
              :class="{ 'fas fa-chevron-right': value, 'fas fa-chevron-down': !value }"
              style="width: 1rem; height: 1rem"
              @click="() => click()"
            />
          </template>
          <template #item="{item}">
            <div class="input-container">
              <template v-if="item.modelTypeId === modelTypes.LANDING_LINK_GROUP">
                <form-input v-model="item.name" label="Название группы" />
              </template>
              <template v-else-if="item.modelTypeId === modelTypes.LANDING_LINK">
                <form-input v-model="item.name" label="Текст" />
                <form-input v-model="item.value" class="mt-3" label="Ссылка" />
              </template>
              <template v-else-if="item.modelTypeId === modelTypes.LANDING_IMAGE">
                <image-upload v-model="item.image" />
              </template>
              <template v-else-if="item.modelTypeId === modelTypes.LANDING_TEXT">
                <form-input type="textarea" v-model="item.content" label="Текст" />
              </template>
              <corner-icon icon="far fa-trash-alt" @click="() => deleteLink(item.id)" />
            </div>
          </template>
        </tree>
      </div>
    </div>
  </div>
  <background-editor v-model="landing.background" @change="++reloadKey" />
  <modal-window id="addEntity" hide-footer>
    <template #title>
      Добавление блоков
    </template>
    <template #body>
      <div class="add-entity-controls">
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_LINK)">
          <i class="fas fa-link"></i>
        </button>
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_LINK_GROUP)">
          <i class="far fa-folder-open"></i>
        </button>
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_IMAGE)">
          <i class="far fa-image"></i>
        </button>
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_TEXT)">
          <i class="fas fa-font" />
        </button>
      </div>
    </template>
  </modal-window>
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
import CornerIcon from "@/components/CornerIcon.vue";
import BackgroundEditor from "@/components/BackgroundEditor.vue";
import Tree from "@/components/Tree.vue";
import ModelType from "@/common/service/model.type";
import ModalWindow from "@/components/ModalWindow.vue";
import ImageUpload from "@/components/ImageUpload.vue";

@Options({
  components: { FormInput, FormGroup, GoBack, CornerIcon, BackgroundEditor, Tree, ModalWindow, ImageUpload },
})
export default class LandingEdit extends Vue {
  private reloadKey: number = 0;

  get id() {
    return Number(this.$router.currentRoute.value.params.id);
  }

  get landing() {
    return landingStore.context(this.$store).getters.landingById(this.id)!;
  }

  get modelTypes() {
    return ModelType;
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
