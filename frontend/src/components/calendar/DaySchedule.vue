<template>
  <div class="schedule p-3 rounded-10 bg-white ms-3 h-fit-content">
    <div class="hours">
      <!-- WORK HOURS -->
      <div v-for="workHour in workHours" :key="workHour" class="work-hour">{{ ("00" + workHour).slice(-2) }}:00</div>
      <!-- LAST HOUR -->
      <div class="work-hour">{{ ("00" + (workHours[workHours.length - 1] + 1)).slice(-2) }}:00</div>
    </div>

    <div class="field">
      <!-- DIVIDERS -->
      <div class="dividers">
        <template v-for="workHour in workHours" :key="workHour">
          <div class="divider" />
        </template>
        <!-- LAST HOUR -->
        <div class="divider last" />
      </div>
      <!-- ENTRIES -->
      <div
        class="entries"
        :style="
          `
          grid-template-columns: repeat(${maxColumnCount}, 1fr);
          grid-template-rows: repeat(${workHours[workHours.length - 1] - workHours[0] + 1}, 50px);
          `
        "
        @mousedown="startSelection"
        @mousemove="recalcSelection"
        @mouseup="endSelection"
      >
        <calendar-entry
          v-for="entry in entries"
          :key="entry.id"
          :class="{
            'no-pointer-events': selectionCommencing,
          }"
          :value="entry"
          @show="showingCalendarEntry = true"
          @hide="showingCalendarEntry = false"
          @update="load"
        />
        <calendar-entry
          v-if="newEntry.timeStart"
          :value="newEntry"
          :class="{
            'no-pointer-events': selectionCommencing,
          }"
          isNew
          @update="load"
          :style="`grid-column: ${newEntry.left}`"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.no-pointer-events {
  pointer-events: none !important;
}

$sideLength: 50px;
.schedule {
  display: flex;
  width: calc(500px + 2rem + 45px);
  .work-hour {
    display: flex;
    align-items: flex-start;
    height: $sideLength;
    min-width: 45px;
    text-align: end;
    line-height: 2px;
  }

  .field {
    width: 500px;
    position: relative;
    .dividers {
      width: 100%;
      .divider {
        width: 100%;
        height: $sideLength;
        background-color: rgba(lightskyblue, 0.25);
        border-top: 2px solid lightskyblue;
        &.last {
          background-color: transparent;
        }
      }
    }
  }
  .entries {
    position: absolute;
    display: grid;
    top: 0px;
    grid-auto-rows: $sideLength;
    grid-auto-flow: dense;
    width: 100%;
  }
}
</style>

<script lang="ts">
import { calendarStore, LOAD_ALL_CALENDAR_ENTRIES } from "@/store/modules/calendar.store";
import CalendarEntry from "./CalendarEntry.vue";
import { Options, Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";
import { authStore } from "@/store/modules/auth.store";
import Api from "@/common/api";

@Options({
  components: { CalendarEntry },
})
export default class DayScheduleComponent extends Vue {
  @Prop(String) readonly date!: string;

  get entries() {
    return calendarStore.context(this.$store).state.entries;
  }
  get workHours() {
    return authStore.context(this.$store).getters.workHours;
  }
  get entryGrid() {
    return document.querySelector(".entries")!;
  }
  readonly maxColumnCount = 10;
  readonly defaultNewEntry = {
    date: "",
    timeStart: 0,
    timeEnd: 0,
    left: 0,
    note: "",
    modelId: undefined,
    modelTypeId: undefined,
    backgroundColor: "#ffffff",
  };

  @Watch("date") onDateChanged() {
    this.load();
  }

  newEntry = Object.assign({}, this.defaultNewEntry);
  selectionCommencing = false;
  showingCalendarEntry = false;

  get [LOAD_ALL_CALENDAR_ENTRIES]() {
    return () =>
      calendarStore.context(this.$store).dispatch(LOAD_ALL_CALENDAR_ENTRIES, {
        date: {
          from: this.date,
          to: this.date,
        },
      });
  }

  created() {
    this.resetNewEntry();
  }
  mounted() {
    this.load();
  }
  load() {
    if (this.selectionCommencing) {
      return;
    }

    this.resetNewEntry();
    this[LOAD_ALL_CALENDAR_ENTRIES]().then((response) => {
      !response.success && this.$notifications.error(response.error);
    });
  }
  resetNewEntry() {
    this.newEntry = JSON.parse(JSON.stringify(this.defaultNewEntry));
    this.newEntry.date = this.date;
  }
  startSelection({ offsetX, offsetY, button }: MouseEvent) {
    if (this.showingCalendarEntry || button !== 0) {
      return;
    }

    this.selectionCommencing = true;
    this.resetNewEntry();
    this.newEntry.timeStart = Math.floor(offsetY / 50) + this.workHours[0];
    this.recalcSelection({ offsetY: offsetY });
    this.newEntry.left = Math.ceil((offsetX / this.entryGrid.getBoundingClientRect().width) * this.maxColumnCount);
  }
  recalcSelection({ offsetY }: { [key: string]: any }) {
    if (this.showingCalendarEntry || !this.selectionCommencing) {
      return;
    }
    const newValue = Math.floor(offsetY / 50) + this.workHours[0] + 1;
    if (this.newEntry.timeEnd === newValue) {
      return;
    }
    this.newEntry.timeEnd = newValue;
  }
  endSelection() {
    if (this.showingCalendarEntry) {
      return;
    }
    this.selectionCommencing = false;

    Api.calendar.saveEntry(this.newEntry).then((response) => {
      response.success ? this.load() : this.$notifications.error(response.error);
    });
  }
}
</script>
