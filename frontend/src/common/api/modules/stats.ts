import Requestor from "@/common/service/requestor";
import StatConditionSaveForm from "@/types/stats/StatConditionSaveForm";
import StatFieldSaveForm from "@/types/stats/StatFieldSaveForm";
import StatOrderSaveForm from "@/types/stats/StatOrderSaveForm";
import StatSaveForm from "@/types/stats/StatSaveForm";

export default class StatsApi {
  getAll = () => Requestor.post("/statistics/getAll");
  getOne = (id: number) => Requestor.post("/statistics/getOne", { id: id });
  view = (id: number) => Requestor.post("/statistics/view", { id: id });
  save = (form: StatSaveForm) => Requestor.post("/statistics/save", form);
  delete = (id: number) => Requestor.post("/statistics/delete", { id: id });
  common = () => Requestor.post("/statistics/common");
  download = (id: number) => Requestor.post("/statistics/download", { id: id });
  component = {
    save: (form: StatFieldSaveForm | StatConditionSaveForm | StatOrderSaveForm) =>
      Requestor.post("/statistics/saveComponent", form),
    delete: (id: number) => Requestor.post("/statistics/deleteComponent", { id: id }),
  };
}
