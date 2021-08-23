import Requestor from "@/common/service/requestor";

export default class SiteApi {
  test = () => Requestor.post("/site/test");
}
