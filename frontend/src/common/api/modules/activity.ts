import Requestor from "@/common/service/requestor";
import Activity from "@/types/activity/Activity";

export default class ActivityApi {
  getAll = () => Requestor.post("/activity/getAll");
  save = (activity: Activity) => Requestor.post("/activity/save", activity);
  delete = (activityId: number) => Requestor.post("/activity/delete", { id: activityId });
}
