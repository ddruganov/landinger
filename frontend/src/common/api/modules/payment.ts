import Requestor from "@/common/service/requestor";

export default class PaymentApi {
  service = {
    all: () => Requestor.get('/payment/allServices'),
    durations: (serviceId: number) => Requestor.get('/payment/allServiceDurations', { serviceId: serviceId }),
    paid: {
      all: () => Requestor.get('/payment/allPaidServices')
    }
  };
}
