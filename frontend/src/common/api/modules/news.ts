import Requestor from "@/common/service/requestor";
import ArticleSaveForm from "@/types/news/ArticleSaveForm";

export default class NewsApi {
  getAll = (page = 1, filter = {}) => Requestor.post("/news/getAll", { page: page, filter: filter });
  getOne = (id: number) => Requestor.post("/news/getOne", { id: id });
  save = (form: ArticleSaveForm = {}) => Requestor.post("/news/save", form);
  remove = (id: number) => Requestor.post("/news/delete", { id: id });
  toggle = (id: number) => Requestor.post("/news/toggle", { id: id });
}
