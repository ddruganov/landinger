import Requestor from "@/common/service/requestor";
import RequestOptions from "@/types/api/RequestOptions";
import CalendarEntrySaveForm from "@/types/calendar/CalendarEntrySaveForm";

export default class CalendarApi {
  getEntries = (filter: Object) => Requestor.post("/calendar/getEntries", { filter: filter });
  getEntryInfo = (id: number) =>
    Requestor.post("/calendar/getEntryInfo", { id: id }, new RequestOptions().setIgnoredByState(true));
  saveEntry = (form: CalendarEntrySaveForm) =>
    Requestor.post("/calendar/saveEntry", form, new RequestOptions().setIgnoredByState(true));
  deleteEntry = (id: number) => Requestor.post("/calendar/deleteEntry", { id: id });
  autocomplete = (filter: Object) =>
    Requestor.post("/calendar/autocomplete", { filter: filter }, new RequestOptions().setIgnoredByState(true));
}
