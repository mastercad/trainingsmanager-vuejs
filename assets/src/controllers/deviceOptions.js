import axios from "axios";

export default {
  create(deviceOption) {
    return axios.post("/api/device_options", {
      name: deviceOption.name,
      defaultValue: deviceOption.defaultValue
    });
  },
  update(deviceOption) {
    return axios.put("/api/device_options/"+deviceOption.id, {
      name: deviceOption.name,
      defaultValue: deviceOption.defaultValue
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
