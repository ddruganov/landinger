import Requestor from "@/common/service/requestor";
import UserOrganizationSaveForm from "@/types/auth/userOrganization/UserOrganizationSaveForm";
import PasswordSaveForm from "@/types/settings/PasswordSaveForm";

export default class SettingsApi {
  savePassword = (form: PasswordSaveForm) => Requestor.post("/settings/savePassword", form);
  organization = {
    setDefault: (organizationId: number) =>
      Requestor.post("/settings/setDefaultOrganizationId", { organizationId: organizationId }),
    getInfo: () => Requestor.get("/settings/getOrganizationInfo"),
    save: (form: UserOrganizationSaveForm) => Requestor.post("/settings/saveOrganization", form),
  };
  token = {
    getAll: () => Requestor.post("/settings/getTokens"),
    remove: (id: number) => Requestor.post("/settings/removeToken", { id: id }),
  };
}
