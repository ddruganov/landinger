import Requestor from "@/common/service/requestor";
import Landing from "@/types/landing/Landing";

export default class LandingApi {
  all = () => Requestor.get("/landing/all");
  create = () => Requestor.post("/landing/create");
  save = (landing: Landing) => Requestor.patch("/landing/save", landing);

  link = {
    create: (landingId: number) => Requestor.post("/landing/create_link", { landingId: landingId })
  }
}
