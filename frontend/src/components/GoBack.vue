<template>
  <a :href="link" class="w-fit-content d-flex align-items-center link-primary">
    <i class="fas fa-chevron-left" />
    <span class="ms-1">назад</span>
  </a>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class GoBack extends Vue {
  @Prop(String) readonly link!: string;
}
</script>
