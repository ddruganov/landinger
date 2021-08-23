import Requestor from "@/common/service/requestor";
import RequestOptions from "@/types/api/RequestOptions";
import Client from "@/types/client/Client";

export default class ClientApi {
  getAll = (page = 1, filter = {}) => Requestor.post("/client/getAll", { page: page, filter: filter });
  autocomplete = (filter = {}) =>
    Requestor.post("/client/autocomplete", { filter: filter }, new RequestOptions().setIgnoredByState(true));
  getOne = (organizationClientId: number) =>
    Requestor.post("/client/getOne", { organizationClientId: organizationClientId });
  save = (form: Client = {}) => Requestor.post("/client/save", form);
  getPassInstances = (organizationClientId: number, filter: Object) =>
    Requestor.post("/client/getPassInstances", { organizationClientId: organizationClientId, filter: filter });
  getVisits = (organizationClientId: number) =>
    Requestor.post("/client/getVisits", { organizationClientId: organizationClientId });
  delete = (organizationClientId: number) =>
    Requestor.post("/client/delete", { organizationClientId: organizationClientId });
}
