<template>
  <router-link :to="link" class="go-back fc">
    <i class="icon fas fa-chevron-left" />
    <span class="caption">&nbsp;назад</span>
  </router-link>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class GoBack extends Vue {
  @Prop(String) readonly link!: string;
}
</script>
