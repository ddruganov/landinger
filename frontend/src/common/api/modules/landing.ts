import Requestor from "@/common/service/requestor";
import Landing from "@/types/landing/Landing";

export default class LandingApi {
  common = () => Requestor.get("/landing/common");
  all = () => Requestor.get("/landing/all");
  create = () => Requestor.post("/landing/create");
  delete = (id: number) => Requestor.delete("/landing/delete", { id: id });
  save = (landing: Landing) => Requestor.patch("/landing/save", landing);

  entity = {
    create: (landingId: number, modelTypeId: number, parentId: number | undefined) => Requestor.post("/landing/createEntity", { landingId: landingId, modelTypeId: modelTypeId, parentId: parentId }),
    delete: (landingId: number, id: number) => Requestor.delete("/landing/deleteEntity", { landingId: landingId, id: id })
  }
}
