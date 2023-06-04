import axios from "axios";

export default {
  create(name, seoLink, color) {
    return axios.post("/api/muscle_groups", {
      name: name,
      seoLink: seoLink,
      color: color
    });
  },
  update(id, name, seoLink, color) {
    return axios.put("/api/muscle_groups/"+id, {
      name: name,
      seoLink: seoLink,
      color: color
    });
  },
  delete(id) {
    return axios.delete("/api/muscle_groups/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/muscle_groups");
  },
  findMuscleGroup(id) {
    return axios.get("/api/muscle_groups/"+id);
  }
};
