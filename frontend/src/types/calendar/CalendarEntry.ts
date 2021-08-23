type CalendarEntry = {
  [key: string]: number | string | undefined;
  id: number;
  date: string;
  timeStart: number;
  timeEnd: number;
  modelId?: number;
  modelTypeId?: number;
  backgroundColor: string;
  note?: string;
};
export default CalendarEntry;
