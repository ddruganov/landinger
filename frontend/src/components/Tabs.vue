<template>
  <div class="tabs">
    <div class="header">
      <span
        class="tab-name"
        v-for="(item, i) in items"
        :key="i"
        :class="{ active: i === activeTab }"
        @click="() => setActiveTab(i)"
        >{{ item.name }}</span
      >
    </div>
    <div class="body">
      <slot :name="`tab${activeTab}`" :item="items[activeTab]" />
    </div>
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop } from "vue-property-decorator";

type Tab = {
  name: string;
};

export default class Tabs extends Vue {
  @Prop(Array) readonly items!: Tab[];
  @Prop(Number) readonly startValue!: number;

  private activeTab: number = 0;

  mounted() {
    this.setActiveTab((this.startValue || 1) - 1);
  }

  private setActiveTab(index: number) {
    this.activeTab = index;
    this.$emit("switch", this.items[this.activeTab]);
  }
}
</script>
