import Requestor from "@/common/service/requestor";
import RequestOptions from "@/types/api/RequestOptions";
import User from "@/types/auth/User";

export default class StaffApi {
  getAll = (page = 1, filter = {}) => Requestor.post("/staff/getAll", { page: page, filter: filter });
  getOne = (id: number) => Requestor.post("/staff/getOne", { id: id });
  save = (form: User) => Requestor.post("/staff/save", form);
  autocomplete = (filter = {}) =>
    Requestor.post("/staff/autocomplete", { filter: filter }, new RequestOptions().setIgnoredByState(true));
}
