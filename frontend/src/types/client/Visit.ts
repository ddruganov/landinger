type Visit = {
  id: number;
  datetime: string;
  hoursSpent: number;
  passInstance: { id: number; name: string };
};
export default Visit;
