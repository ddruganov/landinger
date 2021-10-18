<template>
  <div class="landing-edit-container" :style="`background: ${landing.background.value}`" :key="reloadKey">
    <div class="container">
      <div class="block topbar">
        <go-back link="/" />
        <button class="button wfc" @click="() => save()">Сохранить</button>
      </div>
      <div class="block column mb-3">
        <form-input class="mb-3" v-model="landing.name" label="Название" />
        <form-input class="mb-3" v-model="landing.alias" label="Алиас" prefix="linktome.site/" />
        <button class="button fc me-auto" modal-trigger="chooseBackground">Выбрать фон</button>
      </div>
      <div class="block controls">
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_LINK)">
          <i class="icon fas fa-plus" />
          <div class="header">Создать ссылку</div>
        </button>
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_LINK_GROUP)">
          <i class="icon fas fa-plus" />
          <div class="header">Создать группу</div>
        </button>
        <button class="button create fc" @click="() => createEntity(modelTypes.LANDING_IMAGE)">
          <i class="icon fas fa-plus" />
          <div class="header">Добавить фото</div>
        </button>
      </div>
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
                <form-input v-model="item.value" class="mt-3" label="Значение" />
              </template>
              <template v-else-if="item.modelTypeId === modelTypes.LANDING_IMAGE">
                <form-input v-model="item.url" label="Изображение" />
                <img class="landing-image" :src="item.url" />
              </template>
              <corner-icon icon="far fa-trash-alt" @click="() => deleteLink(item.id)" />
            </div>
          </template>
        </tree>
      </div>
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
import CornerIcon from "@/components/CornerIcon.vue";
import BackgroundEditor from "@/components/BackgroundEditor.vue";
import Tree from "@/components/Tree.vue";
import ModelType from "@/common/service/model.type";

@Options({
  components: { FormInput, FormGroup, GoBack, CornerIcon, BackgroundEditor, Tree },
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
