import Requestor from "@/common/service/requestor";
import PassInstanceCreateForm from "@/types/passInstance/PassInstanceCreateForm";
import PassInstanceVisitForm from "@/types/passInstance/PassInstanceVisitForm";

export default class PassInstanceApi {
  getAll = (page = 1, filter = null) =>
    Requestor.post("/pass-instance/getAll", { page: page, filter: filter }).catch((e) => e);
  getOne = (id: number) => Requestor.post("/pass-instance/getOne", { id: id });
  create = (form: PassInstanceCreateForm) => Requestor.post("/pass-instance/create", form);
  visit = (form: PassInstanceVisitForm) => Requestor.post("/pass-instance/visit", form);
}
