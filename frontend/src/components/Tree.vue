<template>
  <draggable-list v-if="items.length" v-model="items" :style="`padding-left: ${depth * 2}rem;`">
    <template #block="{item}">
      <div class="block column">
        <slot name="item" :item="item" />
      </div>
      <tree
        v-if="item.children.length"
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
    </template>
  </draggable-list>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import CornerIcon from "./CornerIcon.vue";
import DraggableList from "./DraggableList.vue";
import FormInput from "./form/FormInput.vue";

type TreeItem = {
  [key: string]: any;
  weight: number;
  children: TreeItem[];
};

@Options({
  components: { FormInput, CornerIcon, DraggableList },
})
export default class Tree extends Vue {
  @Prop(Array) readonly modelValue!: TreeItem[];
  @Prop({ type: Number, default: 0 }) readonly depth!: number;

  private items: TreeItem[] = [];
  mounted() {
    this.items = this.modelValue;
  }
}
</script>
