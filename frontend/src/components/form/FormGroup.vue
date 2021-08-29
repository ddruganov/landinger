<template>
  <div class="form-group-wrapper">
    <label class="label">{{ label }}</label>
    <slot></slot>
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class FormGroup extends Vue {
  @Prop(String) readonly label!: string;
}
</script>
