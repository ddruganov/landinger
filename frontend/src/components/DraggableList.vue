<template>
  <div :id="id" class="draggable-list">
    <div class="spacer" data-spacer-index="-1">
      <div class="border" />
    </div>
    <template v-for="(item, i) in items" :key="i">
      <div
        class="block"
        :data-block-index="i"
        draggable="true"
        @dragstart="() => onDragStart(i)"
        @dragover="(e) => onDragOver(e, i)"
        @dragend="(e) => onDragEnd(e)"
      >
        <div class="block-container" :class="{ 'no-pointer-events': isDragging }">
          <slot name="block" :item="item" />
        </div>
      </div>
      <div class="spacer" :data-spacer-index="i">
        <div class="border" />
      </div>
    </template>
  </div>
</template>
<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

export default class DraggableList extends Vue {
  @Prop(Array) readonly modelValue!: any[];

  private items: any[] = [];
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

  private onDragStart(draggedItemIndex: number) {
    this.draggedItemIndex = draggedItemIndex;
    this.insertAfter = false;
  }

  private onDragOver(e: DragEvent, draggedOverItemIndex: number) {
    const target = e.target as HTMLDivElement;
    const boundingRect = target.getBoundingClientRect();
    const centerY = boundingRect.y + boundingRect.height / 2;
    const newValue = e.y > centerY;

    if (newValue === this.insertAfter) {
      return;
    }

    this.draggedOverItemIndex = draggedOverItemIndex;

    this.insertAfter = newValue;
    const blockIndex = Number(target.dataset.blockIndex) + (newValue ? 0 : -1);
    this.highlightSpacer(blockIndex);
  }

  private onDragEnd() {
    this.recalcWeights();
    this.draggedItemIndex = -1;
    this.draggedOverItemIndex = -1;
    this.highlightSpacer(undefined);
  }

  private recalcWeights() {
    if (this.draggedItemIndex === this.draggedOverItemIndex) {
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
        const spacerIndex = Number((spacer as HTMLDivElement).dataset.spacerIndex);
        spacerIndex === index && spacer.classList.add("active");
      });
  }
}
</script>
