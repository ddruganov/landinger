import Requestor from "@/common/service/requestor";
import RequestOptions from "@/types/api/RequestOptions";

export default class ImageApi {
  upload = (image: FormData) => Requestor.post("/image/upload", image, new RequestOptions().setStringify(false));
}
