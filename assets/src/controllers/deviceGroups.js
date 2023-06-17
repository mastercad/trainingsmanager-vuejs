import axios from "axios";

export default {
  create(deviceGroup) {
    return axios.post("/api/device_groups", {
      name: deviceGroup.name,
      seoLink: deviceGroup.seoLink
    });
  },
  update(deviceGroup) {
    return axios.put("/api/device_groups/"+deviceGroup.id, {
      name: deviceGroup.name,
      seoLink: deviceGroup.seoLink
    });
  },
  delete(id) {
    return axios.delete("/api/device_groups/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/device_groups");
  },
  findExerciseType(id) {
    return axios.get("/api/device_groups/"+id);
  }
};
