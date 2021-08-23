import Requestor from "@/common/service/requestor";
import CoachSaveForm from "@/types/coach/CoachSaveForm";

export default class CoachApi {
  getAll = (page = 1, filter = {}) => Requestor.post("/coach/getAll", { page: page, filter: filter });
  autocomplete = () => Requestor.post("/coach/autocomplete");
  save = (form: CoachSaveForm) => Requestor.post("/coach/save", form);
  toggle = (id: number) => Requestor.post("/coach/toggle", { id: id });
  delete = (id: number) => Requestor.post("/coach/delete", { id: id });
}
