import Requestor from "@/common/service/requestor";

export default class AuthApi {

  getSocialLinks = () => Requestor.get('/auth/getSocialLinks');
  socialNetworkAuth = (params: any) => Requestor.post('/auth/social', params);

  logout = () => Requestor.post("/auth/logout");
  refresh = () => Requestor.post("/auth/refresh");

  getCurrentUser = () => Requestor.post("/auth/getCurrentUser");
}
