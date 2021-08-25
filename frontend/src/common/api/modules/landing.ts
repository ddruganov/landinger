import Requestor from "@/common/service/requestor";

export default class LandingApi {
  all = () => Requestor.get("/landing/all");
  create = () => Requestor.post("/landing/create");
}
