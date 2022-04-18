import axios from "axios";

export default {
  create(name, defaultValue) {
    return axios.post("/api/device_options", {
      name: name,
      defaultValue: defaultValue
    });
  },
  update(id, name, defaultValue) {
    return axios.put("/api/device_options/"+id, {
      name: name,
      defaultValue: defaultValue
    });
  },
  delete(id) {
    return axios.delete("/api/device_options/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/device_options");
  },
  findDeviceOption(id) {
    return axios.get("/api/device_options/"+id);
  }
};
