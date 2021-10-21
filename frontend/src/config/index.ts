let hosts: any = {};
if (process.env.NODE_ENV === 'development') {
  hosts = {
    api: "http://localhost:8001",
    admin: "http://localhost:8080",
    client: "http://localhost:8005",
    service: "http://localhost:8007",
  };
} else {
  hosts = {
    api: "http://api.linktome.site",
    admin: "http://admin.linktome.site",
    client: "http://linktome.site",
    service: "http://service.linktome.site",
  };
}

export default {
  hosts: hosts
};
