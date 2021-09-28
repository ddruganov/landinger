<template>
  <draggable-list v-if="items.length" v-model="items" :style="`padding-left: ${depth * 2}rem;`">
    <template #block="{item}">
      <div class="block">
        <slot v-if="item.children.length" name="fold" :value="isItemFolded(item)" :click="() => toggleFold(item)" />
        <slot name="item" :item="item" />
      </div>
      <grow-transition>
        <tree
          v-if="item.children.length"
          v-show="!isItemFolded(item)"
          v-model="item.children"
          :depth="depth + 1"
          @dragover.prevent
          @dragstart.stop
          @dragend.stop
        >
          <template #item="{item}">
            <slot name="item" :item="item" />
          </template>
        </tree>
      </grow-transition>
    </template>
  </draggable-list>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import CornerIcon from "./CornerIcon.vue";
import DraggableList from "./DraggableList.vue";
import FormInput from "./form/FormInput.vue";
import GrowTransition from "./transitions/GrowTransition.vue";

type TreeItem = {
  [key: string]: any;
  id: number;
  weight: number;
  children: TreeItem[];
};

@Options({
  components: { FormInput, CornerIcon, DraggableList, GrowTransition },
})
export default class Tree extends Vue {
  @Prop(Array) readonly modelValue!: TreeItem[];
  @Prop({ type: Number, default: 0 }) readonly depth!: number;

  private items: TreeItem[] = [];
  mounted() {
    this.items = this.modelValue;
  }

  private getLocalStorageFoldKey(item: TreeItem) {
    return `treeItemFolded_${item.id}`;
  }

  private isItemFolded(item: TreeItem) {
    return window.localStorage.getItem(this.getLocalStorageFoldKey(item)) === "true";
  }

  private toggleFold(item: TreeItem) {
    window.localStorage.setItem(this.getLocalStorageFoldKey(item), String(!this.isItemFolded(item)));
    this.$forceUpdate();
  }
}
</script>
