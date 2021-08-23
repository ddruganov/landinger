import ActivityApi from "@/common/api/modules/activity";
import AuthApi from "@/common/api/modules/auth";
import CalendarApi from "./modules/calendar";
import ClientApi from "./modules/client";
import CoachApi from "./modules/coach";
import ImageApi from "./modules/image";
import NewsApi from "./modules/news";
import PassApi from "./modules/pass";
import PassInstanceApi from "./modules/passInstance";
import SettingsApi from "./modules/settings";
import SiteApi from "./modules/site";
import StaffApi from "./modules/staff";
import StatsApi from "./modules/stats";

export default class Api {
  public static activity = new ActivityApi();
  public static auth = new AuthApi();
  public static calendar = new CalendarApi();
  public static client = new ClientApi();
  public static coach = new CoachApi();
  public static image = new ImageApi();
  public static news = new NewsApi();
  public static pass = new PassApi();
  public static passInstance = new PassInstanceApi();
  public static settings = new SettingsApi();
  public static site = new SiteApi();
  public static staff = new StaffApi();
  public static stats = new StatsApi();

  public static getModule(name: string) {
    switch (name) {
      case "calendar":
        return this.calendar;
      case "pass":
        return this.pass;
      case "staff":
        return this.staff;
      case "client":
        return this.client;
      case "coach":
        return this.coach;
      default:
        throw new Error("Unknown module requested");
    }
  }
}
