import axios from "axios";

export default {
  create(name, defaultValue) {
    return axios.post("/api/exercise_options", {
      name: name,
      defaultValue: defaultValue
    });
  },
  update(id, name, defaultValue) {
    return axios.put("/api/exercise_options/"+id, {
      name: name,
      defaultValue: defaultValue
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
