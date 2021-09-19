<template>
  <div class="corner-icon">
    <i class="icon" :class="icon" />
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class CornerIcon extends Vue {
  @Prop(String) readonly icon!: string;
}
</script>
