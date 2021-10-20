import Requestor from "@/common/service/requestor";
import config from "@/config";
import RequestOptions from "@/types/api/RequestOptions";

export default class MultimediaApi {

  get serviceRequestOptions(): RequestOptions {
    return new RequestOptions().setBaseUrl(config.hosts.service).setStringify(false);
  }

  image = {
    upload: (image: FormData) => Requestor.post('/multimedia/uploadImage', image, this.serviceRequestOptions)
  };
}
