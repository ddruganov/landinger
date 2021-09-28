<template>
  <div :id="id" class="draggable-list">
    <div class="spacer" data-spacer-index="-1">
      <div class="border" />
    </div>
    <template v-for="(item, i) in items" :key="i">
      <div
        class="w-100"
        draggable="true"
        @dragstart="(e) => onDragStart(e, i)"
        @dragover="(e) => onDragOver(e, i, item)"
        @dragend="(e) => onDragEnd(e)"
      >
        <slot name="block" :item="item" :class="{'no-pointer-events': isDragging}" />
      </div>
      <div class="spacer" :data-spacer-index="i">
        <div class="border" />
      </div>
    </template>
  </div>
</template>

<script lang="ts">
import {Vue} from "vue-class-component";
import {Prop} from "vue-property-decorator";

type DraggableItem = {
  [key: string]: any;
  id: number;
  weight: number;
};

export default class DraggableList extends Vue {
  @Prop(Array) readonly modelValue!: DraggableItem[];

  private lastDragOverTimestamp: number = 0;

  private items: DraggableItem[] = [];
  mounted() {
    this.items = this.modelValue;
  }

  private id = "DraggableList_" + Date.now();

  private draggedItemIndex: number = -1;
  private draggedOverItemIndex: number = -1;
  private insertAfter = false;

  get isDragging() {
    return this.draggedItemIndex !== -1;
  }

  private onDragStart(e: DragEvent, draggedItemIndex: number) {
    e.target;
    e.stopImmediatePropagation();
    e.stopPropagation();
    this.draggedItemIndex = draggedItemIndex;
    this.insertAfter = false;
  }

  counter = 0;

  private onDragOver(e: DragEvent, draggedOverItemIndex: number, item: DraggableItem) {
    e.preventDefault();
    if (Date.now() - this.lastDragOverTimestamp < 100) {
      return;
    }
    this.lastDragOverTimestamp = Date.now();

    const closestDraggableList = (e.target as HTMLElement).closest(".draggable-list");
    console.log(this.counter++, closestDraggableList);
    if (!closestDraggableList) {
      return;
    }
    if (closestDraggableList.id !== this.id) {
      return;
    }

    item.id;
    // if (this.items.length <= draggedOverItemIndex) {
    //   return;
    // }
    // if (!this.items.find((i) => i.id === item.id)) {
    //   return;
    // }

    // if (!this.isDragging) {
    //   return;
    // }

    const target = e.target as HTMLDivElement;
    const boundingRect = target.getBoundingClientRect();
    const centerY = boundingRect.y + boundingRect.height / 2;
    const newValue = e.y > centerY;

    if (newValue === this.insertAfter) {
      return;
    }

    this.draggedOverItemIndex = draggedOverItemIndex;

    this.insertAfter = newValue;
    this.highlightSpacer(draggedOverItemIndex + (newValue ? 0 : -1));
  }

  private onDragEnd(e: DragEvent) {
    e.target;
    e.stopImmediatePropagation();
    e.stopPropagation();

    this.recalcWeights();
    this.draggedItemIndex = -1;
    this.draggedOverItemIndex = -1;
    this.highlightSpacer(undefined);
  }

  private recalcWeights() {
    // cannot place item before/after itself
    if (this.draggedItemIndex === this.draggedOverItemIndex) {
      return;
    }

    // cannot place item before next or after previous item
    if (this.draggedItemIndex === this.draggedOverItemIndex + (this.insertAfter ? 1 : -1)) {
      return;
    }

    const draggedItem = this.items[this.draggedItemIndex];
    this.items.splice(this.draggedItemIndex, 1);
    this.items.splice(this.draggedOverItemIndex, 0, draggedItem);
    this.items.forEach((item, index) => {
      item.weight = (index + 1) * 10;
    });

    this.$emit("update:modelValue", this.items);
    this.$emit("change", this.items);
  }

  private highlightSpacer(index: number | undefined) {
    document
      .getElementById(this.id)
      ?.querySelectorAll(".spacer")
      .forEach((spacer) => {
        spacer.classList.remove("active");
        if (index === undefined) {
          return;
        }

        const spacerIndex = Number((spacer as HTMLDivElement).dataset.spacerIndex);
        spacerIndex === index && spacer.classList.add("active");
      });
  }
}
</script>
