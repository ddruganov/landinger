import Requestor from "@/common/service/requestor";
import User from "@/types/auth/User";

export default class UserApi {
  save = (user: User) => Requestor.patch('/user/save', user);
}
