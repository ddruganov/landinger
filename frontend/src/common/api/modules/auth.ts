import Requestor from "@/common/service/requestor";
import LoginData from "@/types/auth/LoginData";
import RegisterData from "@/types/auth/RegisterData";
import ResetPasswordData from "@/types/auth/ResetPasswordData";

export default class AuthApi {
  register = (data: RegisterData) => Requestor.post("/auth/register", data);
  verifyEmail = (hash: string) => Requestor.post("/auth/verifyEmail", { hash: hash });

  login = (data: LoginData) => Requestor.post("/auth/login", data);
  logout = () => Requestor.post("/auth/logout");
  refresh = () => Requestor.post("/auth/refresh");

  restore = (email: string) => Requestor.post("/auth/restore", { email: email });
  resetPassword = (data: ResetPasswordData) => Requestor.post("/auth/resetPassword", data);

  getCurrentUser = () => Requestor.post("/auth/getCurrentUser");
}
