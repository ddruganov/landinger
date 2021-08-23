<template>
  <div class="date-picker p-3 rounded-10 w-fit-content h-fit-content">
    <div class="controls mb-1">
      <i class="fas fa-angle-double-left text-primary mx-auto" role="button" @click="--monthOffset" />
      <a href="#" class="text-center link-primary mx-auto" @click.prevent="resetSelectDay()">
        {{ months[month] }}, {{ year }}
      </a>
      <i class="fas fa-angle-double-right text-primary mx-auto" role="button" @click="++monthOffset" />
    </div>
    <div class="month row m-0 p-0">
      <div v-for="(dayName, i) in dayNames" :key="i" class="day name text-center text-muted">
        {{ dayName }}
      </div>
      <div
        v-for="(day, i) in monthDays"
        :key="i"
        class="day rounded text-center"
        :class="{
          'text-muted': day.month !== month,
          'bg-success': day.id !== selectedDay.id && day.id === today.id,
          'bg-primary': day.id === selectedDay.id,
          'text-white': day.id === selectedDay.id || day.id === today.id,
        }"
        @click="selectDay(day)"
      >
        {{ day.day }}
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.date-picker {
  background: white;
  .controls {
    display: flex;
    align-items: center;
  }
  .month {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.25rem;
    .day {
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: default;
      &:not(.name) {
        cursor: pointer;
        &:hover {
          background-color: #ddd;
        }
      }
    }
  }
}
</style>

<script lang="ts">
import { Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";

type WeekDay = {
  id: string;
  year: number;
  month: number;
  day: number;
  weekDay: number;
};

export default class DatePicker extends Vue {
  @Prop(String) readonly value = "";

  monthOffset = 0;
  year = 0;
  month = 0;
  today: WeekDay = {
    id: "",
    year: 0,
    month: 0,
    day: 0,
    weekDay: 0,
  };
  selectedDay: WeekDay = {
    id: "",
    year: 0,
    month: 0,
    day: 0,
    weekDay: 0,
  };
  monthDays: WeekDay[] = [];

  get weekDays() {
    return {
      mon: 1,
      tue: 2,
      wed: 3,
      thu: 4,
      fri: 5,
      sat: 6,
      sun: 7,
    };
  }

  get dayNames() {
    return {
      0: "пн",
      1: "вт",
      2: "ср",
      3: "чт",
      4: "пт",
      5: "сб",
      6: "вс",
    };
  }

  get months() {
    return {
      0: "январь",
      1: "февраль",
      2: "март",
      3: "апрель",
      4: "май",
      5: "июнь",
      6: "июль",
      7: "август",
      8: "сентябрь",
      9: "октябрь",
      10: "ноябрь",
      11: "декабрь",
    };
  }

  @Watch("monthOffset") onMonthOffsetChanged() {
    this.load();
  }

  mounted() {
    this.today = this.getDateStructure(new Date());
    this.selectDay(this.today);

    this.load();
  }
  load() {
    this.computeMonthOffset();
    this.monthDays = this.getCurrentMonth();
  }
  computeMonthOffset() {
    const offsetDate = new Date();
    offsetDate.setMonth(offsetDate.getMonth() + this.monthOffset);
    this.year = offsetDate.getFullYear();
    this.month = offsetDate.getMonth();
  }
  getCurrentMonth() {
    let currentMonth = this.getMonthDays(this.year, this.month);

    if (currentMonth[0].weekDay !== this.weekDays.mon) {
      const previousMonth = this.getMonthDays(this.year, this.month - 1);
      currentMonth.unshift(...previousMonth.slice(1 - currentMonth[0].weekDay));
    }

    if (currentMonth[currentMonth.length - 1].weekDay !== this.weekDays.sun) {
      const nextMonth = this.getMonthDays(this.year, this.month + 1);
      currentMonth.push(...nextMonth.slice(0, this.weekDays.sun - currentMonth[currentMonth.length - 1].weekDay));
    }

    return currentMonth;
  }
  getMonthDays(year: number, month: number) {
    const dayCount = new Date(year, month + 1, 0).getDate();

    let output = [];
    for (let i = 0; i < dayCount; i++) {
      const offset = new Date(year, month, i + 1);
      output.push(this.getDateStructure(offset));
    }

    return output;
  }
  selectDay(day: WeekDay) {
    if (this.selectedDay.id === day.id) {
      return;
    }

    if (this.selectedDay.id && this.selectedDay.month !== day.month) {
      this.monthOffset += day.month - this.selectedDay.month;
    }

    this.selectedDay = day;
    this.$emit("select", this.selectedDay.id);
  }
  getDateStructure(date: Date): WeekDay {
    const year = date.getFullYear();
    const month = date.getMonth();
    const day = date.getDate();
    const weekDay = date.getDay() || 7;

    return {
      id: [year, ("00" + (month + 1)).slice(-2), ("00" + day).slice(-2)].join("/"),
      year: year,
      month: month,
      day: day,
      weekDay: weekDay,
    };
  }
  resetSelectDay() {
    this.selectDay(this.today);
    this.monthOffset = 0;
  }
}
</script>
