import axios from "axios";

export default {
  create(name, defaultValue) {
    return axios.post("/api/exercise_types", {
      name: name
    });
  },
  update(id, name, defaultValue) {
    return axios.put("/api/exercise_types/"+id, {
      name: name
    });
  },
  delete(id) {
    return axios.delete("/api/exercise_types/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/exercise_types");
  },
  findExerciseType(id) {
    return axios.get("/api/exercise_types/"+id);
  }
};
