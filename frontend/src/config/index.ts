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
    api: "https://api.linktome.site",
    admin: "https://admin.linktome.site",
    client: "https://linktome.site",
    service: "https://service.linktome.site",
  };
}

export default {
  hosts: hosts
};
