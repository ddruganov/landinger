import Requestor from "@/common/service/requestor";

export default class AuthApi {

  getSocialLinks = () => Requestor.get('/auth/get-social-links');

  logout = () => Requestor.post("/auth/logout");
  refresh = () => Requestor.post("/auth/refresh");

  getCurrentUser = () => Requestor.post("/auth/getCurrentUser");
}
