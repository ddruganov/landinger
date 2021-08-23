<template>
  <div
    class="entry pop"
    @mouseenter="onMouseEnter"
    @mousemove="onMouseMove"
    @mouseleave="onMouseLeave"
    :style="getEntryStyle()"
    :class="{ new: isNew }"
  >
    <div
      v-if="show"
      class="pop-container bg-white border rounded-10 p-3"
      :style="`left: ${mouse.x}px; top: ${mouse.y}px;`"
    >
      <div class="pop-heading d-flex align-items-center justify-content-between mb-3">
        <color-picker v-model="entry.backgroundColor" @change="saveEntry(['backgroundColor'])" />
        <div class="d-flex align-items-center">
          <input type="number" class="time-input rounded" v-model.number="entry.timeStart" @change="saveStartTime" />
          <span>–</span>
          <input type="number" class="time-input rounded" v-model.number="entry.timeEnd" @change="saveEndTime" />
        </div>
        <button :disabled="isNew" class="btn btn-sm btn-danger" @click="deleteEntry">
          <i class="far fa-trash-alt" />
        </button>
      </div>
      <div class="pop-content">
        <custom-remote-select
          v-model="boundModels"
          :maxModelCount="1"
          modelName="calendar"
          promptText="Введите имя клиента"
          class="mb-3"
        />
        <textarea
          class="form-control form-control-sm"
          v-model="entry.note"
          @change="saveEntry(['note'])"
          style="resize: none"
          rows="3"
          placeholder="Введите текст заметки"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.entry {
  margin-top: 2px;
  border: 1px solid white;
  border-radius: 5px;
  &.new {
    border: 3px solid white;
    background: rgba(white, 0.25);
  }
  &.pop {
    .pop-container {
      position: absolute;
      width: 300px;
      .pop-heading {
        .time-input {
          border: none;
          width: 30px;
          text-align: center;
          background: #eee;
          margin: 0 0.25rem;
          appearance: none;
          &::-webkit-outer-spin-button,
          &::-webkit-inner-spin-button {
            appearance: none;
          }
        }
      }
      .pop-footer {
        display: flex;
        justify-content: flex-end;
      }
    }
  }
}
</style>

<script lang="ts">
import Api from "@/common/api";
import ColorPicker from "./ColorPicker.vue";
import CustomRemoteSelect from "@/components/CustomRemoteSelect.vue";
import { Options, Vue } from "vue-class-component";
import { Prop, Watch } from "vue-property-decorator";
import { authStore } from "@/store/modules/auth.store";
import CalendarEntry from "@/types/calendar/CalendarEntry";
import CalendarEntrySaveForm from "@/types/calendar/CalendarEntrySaveForm";

type MousePos = {
  x?: number;
  y?: number;
};

type BoundModel = {
  id?: number;
  modelTypeId?: number;
};

@Options({
  components: { ColorPicker, CustomRemoteSelect },
})
export default class CalendarEntryComponent extends Vue {
  @Prop(Object) readonly value!: CalendarEntry;
  @Prop(Boolean) readonly isNew!: Boolean;

  get workHours() {
    return authStore.context(this.$store).getters.workHours;
  }
  entry: CalendarEntry = this.value;
  show = false;
  mouse: MousePos = {
    x: undefined,
    y: undefined,
  };
  isSaving = false;
  boundModels: BoundModel[] = [];

  @Watch("value", { immediate: true }) onValueChanged() {
    this.entry = this.value;
  }
  @Watch("boundModels", { deep: true }) onBoundModelChanged() {
    const data = this.boundModels.length
      ? this.boundModels[0]
      : {
          id: undefined,
          modelTypeId: undefined,
        };
    this.entry.modelId = data.id;
    this.entry.modelTypeId = data.modelTypeId;
    this.saveEntry(["modelId", "modelTypeId"]);
  }

  mounted() {
    this.boundModels = [
      {
        id: this.entry.modelId,
        modelTypeId: this.entry.modelTypeId,
      },
    ];
  }

  onMouseEnter() {
    if (this.isNew) {
      return;
    }

    this.show = true;
    this.$emit("show");
  }
  onMouseMove(e: MouseEvent) {
    if ((this.mouse.x && this.mouse.y) || this.isNew) {
      return;
    }

    const rect = (e.currentTarget as HTMLElement).parentElement!.getBoundingClientRect();
    this.mouse = {
      x: e.clientX - rect.left,
      y: e.clientY - rect.left,
    };
  }
  onMouseLeave() {
    if (this.isNew) {
      return;
    }

    this.show = false;
    this.$emit("hide");
    this.isNew && this.$emit("update");
    this.mouse = {
      x: undefined,
      y: undefined,
    };
  }
  deleteEntry() {
    this.entry.id &&
      Api.calendar.deleteEntry(this.entry.id).then((response) => {
        if (response.success) {
          this.$emit("update");
        } else {
          this.$notifications.error("Ошибка удаления записи");
        }
      });
  }
  saveEntry(props: string[] = []) {
    if (this.isNew && props.length) {
      return;
    }

    props = props.length ? ["id", ...props] : Object.keys(this.entry);
    let form: CalendarEntrySaveForm = {};
    for (const prop of props) {
      form[prop] = this.entry[prop];
    }

    Api.calendar.saveEntry(form).then((response) => {
      if (!response.success) {
        this.$emit("update");
        this.$notifications.error("Ошибка сохранения записи");
      }
    });
  }
  changeBackgroundColor(value: string) {
    this.entry.backgroundColor = value;
    this.saveEntry(["backgroundColor"]);
  }
  saveStartTime() {
    const startWorkHour = this.workHours[0];
    const endWorkHour = this.workHours[this.workHours.length - 1];

    if (!+this.entry.timeStart) {
      this.entry.timeStart = startWorkHour;
    }
    if (this.entry.timeStart < startWorkHour) {
      this.entry.timeStart = startWorkHour;
    }
    if (this.entry.timeStart > endWorkHour) {
      this.entry.timeStart = endWorkHour;
    }
    this.checkTimeRelation();
    this.saveEntry(["timeStart"]);
  }
  saveEndTime() {
    const startWorkHour = this.workHours[0];
    const endWorkHour = this.workHours[this.workHours.length - 1];

    if (!+this.entry.timeEnd) {
      this.entry.timeEnd = endWorkHour + 1;
    }
    if (this.entry.timeEnd > endWorkHour + 1) {
      this.entry.timeEnd = endWorkHour + 1;
    }
    if (this.entry.timeEnd < startWorkHour) {
      this.entry.timeEnd = startWorkHour;
    }
    this.checkTimeRelation();
    this.saveEntry(["timeEnd"]);
  }
  checkTimeRelation() {
    if (this.entry.timeStart > this.entry.timeEnd) {
      [this.entry.timeStart, this.entry.timeEnd] = [this.entry.timeEnd, this.entry.timeStart];
    }
  }

  getEntryStyle() {
    return `
    grid-row: ${this.entry.timeStart - this.workHours[0] + 1} / ${this.entry.timeEnd - this.workHours[0] + 1};
    background-color: ${this.entry.backgroundColor}c0;
    `;
  }
}
</script>
