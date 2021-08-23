type CalendarEntrySaveForm = {
  [key: string]: string | number | undefined;
  id?: number;
  date?: string;
  timeStart?: number;
  timeEnd?: number;
  modelId?: number;
  modelTypeId?: number;
  backgroundColor?: string;
  note?: string;
};
export default CalendarEntrySaveForm;
