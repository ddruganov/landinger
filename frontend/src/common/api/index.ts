import AuthApi from "@/common/api/modules/auth";
import LandingApi from "./modules/landing";
import MultimediaApi from "./modules/multimedia";
import PaymentApi from "./modules/payment";
import UserApi from "./modules/user";

export default class Api {
  public static auth = new AuthApi();
  public static landing = new LandingApi();
  public static multimedia = new MultimediaApi();
  public static payment = new PaymentApi();
  public static user = new UserApi();

  public static getModule(name: string) {
    switch (name) {
      default:
        throw new Error("Unknown module requested");
    }
  }
}
