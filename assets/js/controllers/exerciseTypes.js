import axios from "axios";

export default {
  create(exerciseType) {
    return axios.post("/api/exercise_types", {
      name: exerciseType.name
    });
  },
  update(exerciseType) {
    return axios.put("/api/exercise_types/"+exerciseType.id, {
      name: exerciseType.name
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
