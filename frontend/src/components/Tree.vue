<template>
  <div
    v-if="items.length"
    :id="id"
    class="subtree"
    :style="`padding-left: ${depth * 2}rem;`"
    @dragstart="(e) => onDragStart(e)"
    @dragover="(e) => onDragOver(e)"
    @dragend="() => onDragEnd()"
  >
    <template v-for="(item, i) in items" :key="i">
      <div class="subtree-middle" :draggable="true">
        <div class="spacer top" :data-spacer-item-id="item.id" :data-insert-after="-1" />
        <div class="block" :data-item-id="item.id">
          <slot v-if="item.children.length" name="fold" :value="isItemFolded(item)" :click="() => toggleFold(item)" />
          <slot name="item" :item="item" />
        </div>
        <tree
          v-if="item.children.length"
          v-show="!isItemFolded(item)"
          v-model="item.children"
          :depth="depth + 1"
          :isTopLevel="false"
        >
          <template #item="{item}">
            <slot name="item" :item="item" />
          </template>
        </tree>
        <div class="spacer bottom" :data-spacer-item-id="item.id" :data-insert-after="1" />
      </div>
    </template>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";
import CornerIcon from "./CornerIcon.vue";
import FormInput from "./form/FormInput.vue";
import GrowTransition from "./transitions/GrowTransition.vue";
import ArrayHelper from "@/common/service/array.helper";
import ModelType from "@/common/service/model.type";

type TreeItem = {
  [key: string]: any;
  id: number;
  weight: number;
  children: TreeItem[];
};

@Options({
  components: { FormInput, CornerIcon, GrowTransition },
})
export default class Tree extends Vue {
  @Prop(Array) readonly modelValue!: TreeItem[];
  @Prop({ type: Number, default: 0 }) readonly depth!: number;
  @Prop({ type: Boolean, default: true }) readonly isTopLevel!: boolean;

  private id = "Tree_" + Date.now();

  private lastDragOverTimestamp: number = 0;
  private draggedItemId: number | undefined = undefined;
  private draggedOverItemId: number | undefined = undefined;
  private insertAfter: number = 0;

  private items: TreeItem[] = [];
  mounted() {
    this.items = this.modelValue;
  }

  private onDragStart(e: DragEvent) {
    if (!this.isTopLevel) {
      return;
    }

    const container = (e.target as HTMLDivElement).querySelector("[data-item-id]") as HTMLDivElement;
    this.draggedItemId = Number(container.dataset.itemId);

    const dragImage = document.createElement("div");
    dragImage.id = "dragImage";
    dragImage.style.width = container.getBoundingClientRect().width + "px";
    dragImage.style.height = container.getBoundingClientRect().height + "px";
    document.body.appendChild(dragImage);
    e.dataTransfer?.setDragImage(dragImage, 0, 0);
  }

  private onDragOver(e: DragEvent) {
    if (!this.isTopLevel) {
      return;
    }

    if (Date.now() - this.lastDragOverTimestamp < 50) {
      return;
    }
    this.lastDragOverTimestamp = Date.now();

    const target = (e.target as HTMLDivElement).closest("[data-item-id]") as HTMLDivElement;
    if (!target) {
      return;
    }
    const draggedOverItemId = Number(target.dataset.itemId);

    const boundingRect = target.getBoundingClientRect();
    const percent = (e.y - boundingRect.y) / boundingRect.height;
    let newValue = 0;
    const margin = 0.33;
    if (percent < margin) {
      newValue = -1;
    } else if (percent > 1 - margin) {
      newValue = 1;
    }

    if (newValue === this.insertAfter) {
      return;
    }

    this.draggedOverItemId = draggedOverItemId;

    this.insertAfter = newValue;
    this.highlightSpacer();
    this.highlightBlock();
  }

  private highlightSpacer() {
    document.querySelectorAll(`#${this.id} .spacer`).forEach((spacer) => {
      spacer.classList.remove("active");
      if (this.draggedOverItemId === undefined) {
        return;
      }

      const el = spacer as HTMLDivElement;

      const spacerItemId = Number(el.dataset.spacerItemId);
      if (spacerItemId !== this.draggedOverItemId) {
        return;
      }

      if (Number(el.dataset.insertAfter) !== this.insertAfter) {
        return;
      }

      spacer.classList.add("active");
    });
  }

  private highlightBlock() {
    document.querySelectorAll(`#${this.id} [data-item-id]`).forEach((block) => {
      block.classList.remove("active");
      if (this.draggedOverItemId === undefined) {
        return;
      }

      if (this.insertAfter !== 0) {
        return;
      }

      const el = block as HTMLDivElement;
      const blockItemId = Number(el.dataset.itemId);
      if (blockItemId !== this.draggedOverItemId) {
        return;
      }
      block.classList.add("active");
    });
  }

  private onDragEnd() {
    if (!this.isTopLevel) {
      return;
    }

    document.getElementById("dragImage")?.remove();

    if (!this.draggedItemId || !this.draggedOverItemId) {
      return;
    }

    // cannot place item before/after itself
    if (this.draggedItemId !== this.draggedOverItemId) {
      this.recalcWeights();
    }

    this.draggedItemId = undefined;
    this.draggedOverItemId = undefined;
    this.highlightSpacer();
    this.highlightBlock();
  }

  private recalcWeights() {
    const list = ArrayHelper.treeToList(this.items);
    const draggedItem = list.find((i) => i.id === this.draggedItemId);
    const draggedOverItem = list.find((i) => i.id === this.draggedOverItemId);

    // cannot put parent element into its children
    if (ArrayHelper.trace(this.items, draggedOverItem).find((item) => item.id === draggedItem.id)) {
      this.$notifications.error("Нельзя так помещать элементы");
      return;
    }

    if (this.insertAfter === 0 && draggedOverItem.modelTypeId !== ModelType.LANDING_LINK_GROUP) {
      this.$notifications.error("Нельзя так помещать элементы");
      return;
    }

    // store previous parent id to get previous siblings
    const prevParentId = draggedItem.parentId;
    const newParentId = !this.insertAfter ? draggedOverItem.id : draggedOverItem.parentId;
    // assign new parent id
    draggedItem.parentId = newParentId;

    // assign previous siblings new weights
    list
      .filter((i) => i.parentId === prevParentId)
      .forEach((sibling, idx) => {
        sibling.weight = (idx + 1) * 10;
      });

    // assign new weight to dragged item to select all siblings coming after it
    draggedItem.weight = draggedOverItem.weight + 5 * this.insertAfter;
    const newSiblings = list
      .filter((item) => item.parentId === newParentId)
      .sort((left, right) => left.weight - right.weight);
    newSiblings.forEach((sibling, idx) => {
      sibling.weight = (idx + 1) * 10;
    });

    this.items = ArrayHelper.listToTree(list);

    this.$emit("update:modelValue", this.items);
    this.$emit("change", this.items);
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
