import axios from "axios";

export default {
  create(name, trainingPlanLayout, owner, parent, active, order) {
    return axios.post("/api/training_plans", {
      name: name,
      trainingPlanLayout: trainingPlanLayout,
      owner: owner,
      parent: parent,
      active: active,
      order: order
    });
  },
  update(id, name, trainingPlanLayout, owner, parent, active, order) {
    return axios.put("/api/training_plans/"+id, {
      name: name,
      trainingPlanLayout: trainingPlanLayout,
      owner: owner,
      parent: parent,
      active: active,
      order: order
    });
  },
  delete(id) {
    return axios.delete("/api/training_plans/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/training_plans");
  }
};
