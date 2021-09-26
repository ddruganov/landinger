<template>
  <div class="tabs">
    <div class="header">
      <span
        class="tab-name"
        v-for="item in items"
        :key="item.id"
        :class="{ active: item.id === activeTabId }"
        @click="() => setActiveTabId(item.id)"
        >{{ item.name }}</span
      >
    </div>
    <div v-if="activeTabId" class="body">
      <slot :name="`${activeTabId}Tab`" :item="items[activeTabIndex]" />
    </div>
  </div>
</template>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";
import Tab from "@/types/tabs/Tab";

export default class Tabs extends Vue {
  @Prop(Array) readonly items!: Tab[];
  @Prop(String) readonly startValue!: string;
  @Watch("startValue") onStartValueChanged() {
    !this.activeTabId && this.setActiveTabId(this.startValue);
  }

  private get activeTabIndex() {
    return this.items.findIndex((i) => i.id === this.activeTabId);
  }

  private activeTabId: string = "";

  mounted() {
    this.onStartValueChanged();
  }

  private setActiveTabId(id: string) {
    this.activeTabId = id;
    this.$emit("switch", this.items[this.activeTabIndex]);
  }
}
</script>
