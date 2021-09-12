<template>
  <go-back link="/" class="mt-5" />

  <div class="block align-items-center justify-content-between mt-3">
    <h3>Редактирование лендинга</h3>
    <button class="button wfc" @click="() => save()">Сохранить</button>
  </div>

  <template v-if="landing">
    <div class="block mt-3">
      <form-input v-model="landing.name" label="Название" />
    </div>

    <div class="d-flex flex-wrap h-100 mt-3">
      <div class="card-container">
        <div class="view-card create-link" @click="() => createLink()">
          <h5 class="header">Создать ссылку</h5>
          <i class="icon fas fa-plus my-auto" />
        </div>
      </div>

      <div class="card-container" v-for="(link, i) in landing.links" :key="i">
        <div class="view-card">
          <form-input v-model="link.name" label="Название" class="mt-3" />
          <form-input v-model="link.value" label="Значение" class="mt-3 h-100" type="textarea" />
        </div>
        <i class="icon corner delete fas fa-times" @click="() => deleteLink(landing.id)" />
      </div>
    </div>
  </template>
</template>

<script lang="ts">
import { CREATE_LANDING_LINK, DELETE_LANDING_LINK, landingStore, SAVE_LANDING } from "@/store/modules/landing.store";
import { Options, Vue } from "vue-class-component";
import FormInput from "@/components/form/FormInput.vue";
import FormGroup from "@/components/form/FormGroup.vue";
import GoBack from "@/components/GoBack.vue";

@Options({
  components: { FormInput, FormGroup, GoBack },
})
export default class LandingEdit extends Vue {
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
    landingStore.context(this.$store).dispatch(DELETE_LANDING_LINK, id);
  }

  save() {
    landingStore.context(this.$store).dispatch(SAVE_LANDING, this.landing);
  }
}
</script>
