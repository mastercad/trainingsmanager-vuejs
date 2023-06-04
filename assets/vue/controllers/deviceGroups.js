import axios from "axios";

export default {
  create(name, seoLink) {
    return axios.post("/api/device_groups", {
      name: name,
      seoLink: seoLink
    });
  },
  update(id, name, seoLink) {
    return axios.put("/api/device_groups/"+id, {
      name: name,
      seoLink: seoLink
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
