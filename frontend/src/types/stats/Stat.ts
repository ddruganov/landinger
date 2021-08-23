import StatCondition from "./StatCondition";
import StatField from "./StatField";

type Stat = {
  id: number;
  name: string;
  components: {
    fields: StatField[];
    conditions: StatCondition[];
  };
};
export default Stat;
