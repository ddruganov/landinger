import AuthApi from "@/common/api/modules/auth";
import LandingApi from "./modules/landing";

export default class Api {
  public static auth = new AuthApi();
  public static landing = new LandingApi();

  public static getModule(name: string) {
    switch (name) {
      default:
        throw new Error("Unknown module requested");
    }
  }
}
