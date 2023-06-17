import axios from "axios";

export default {
  create(exerciseOption) {
    return axios.post("/api/exercise_options", {
      name: exerciseOption.name,
      defaultValue: exerciseOption.defaultValue
    });
  },
  update(exerciseOption) {
    return axios.put("/api/exercise_options/"+exerciseOption.id, {
      name: exerciseOption.name,
      defaultValue: exerciseOption.defaultValue
    });
  },
  delete(id) {
    return axios.delete("/api/exercise_options/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/exercise_options");
  },
  findExerciseOption(id) {
    return axios.get("/api/exercise_options/"+id);
  }
};
