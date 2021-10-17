<template>
  <div class="subtree">
    <template v-for="(item, i) in items" :key="i">
      <div class="subtree-middle" :draggable="true">
        <div class="spacer top fas" :data-spacer-item-id="item.id" :data-insert-after="-1" />
        <slot name="block" :item="item" />
        <div class="spacer bottom fas" :data-spacer-item-id="item.id" :data-insert-after="1" />
      </div>
    </template>
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

type SubtreeItem = {
  [key: string]: any;
  id: number;
  weight: number;
};

export default class Subtree extends Vue {
  @Prop(Array) readonly items!: SubtreeItem[];
}
</script>
