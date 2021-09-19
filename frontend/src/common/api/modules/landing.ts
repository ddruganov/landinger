import Requestor from "@/common/service/requestor";
import Landing from "@/types/landing/Landing";

export default class LandingApi {
  common = () => Requestor.get("/landing/common");
  all = () => Requestor.get("/landing/all");
  create = () => Requestor.post("/landing/create");
  delete = (id: number) => Requestor.delete("/landing/delete", { id: id });
  save = (landing: Landing) => Requestor.patch("/landing/save", landing);

  link = {
    create: (landingId: number) => Requestor.post("/landing/create_link", { landingId: landingId }),
    delete: (landingId: number, id: number) => Requestor.delete("/landing/delete_link", { landingId: landingId, id: id })
  }
}