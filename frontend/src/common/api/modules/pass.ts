import Requestor from "@/common/service/requestor";
import RequestOptions from "@/types/api/RequestOptions";
import PassSaveForm from "@/types/pass/PassSaveForm";

export default class PassApi {
  getAll = (page = 1, filter = {}) => Requestor.post("/pass/getAll", { page: page, filter: filter });
  autocomplete = (filter = {}) =>
    Requestor.post("/pass/autocomplete", { filter: filter }, new RequestOptions().setIgnoredByState(true));
  getOne = (id: number) => Requestor.get("/pass/getOne", { id: id });
  save = (form: PassSaveForm = {}) => Requestor.post("/pass/save", form);
  toggle = (id: number) => Requestor.post("/pass/toggle", { id: id });
  delete = (id: number) => Requestor.post("/pass/delete", { id: id });
  common = () => Requestor.post("/pass/common");
}
