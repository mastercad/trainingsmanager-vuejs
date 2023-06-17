import axios from "axios";

export default {
  create(name) {
    return axios.post("/api/training_plan_layouts", {
      name: name
    });
  },
  update(id, name) {
    return axios.put("/api/training_plan_layouts/"+id, {
      name: name
    });
  },
  delete(id) {
    return axios.delete("/api/training_plan_layouts/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/training_plan_layouts");
  }
};
