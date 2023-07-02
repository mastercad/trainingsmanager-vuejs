import axios from "axios";

export default {
  create(trainingPlanLayout) {
    return axios.post("/api/training_plan_layouts", {
      name: trainingPlanLayout.name
    });
  },
  update(trainingPlanLayout) {
    return axios.put("/api/training_plan_layouts/"+trainingPlanLayout.id, {
      name: trainingPlanLayout.name
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
