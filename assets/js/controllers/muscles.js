import axios from "axios";

export default {
  create(muscle) {
    return axios.post("/api/muscles", {
      name: muscle.name,
      seoLink: muscle.seoLink,
      muscleXMuscleGroups: muscle.muscleXMuscleGroups
    });
  },
  update(muscle) {
    return axios.put("/api/muscles/"+muscle.id, {
      name: muscle.name,
      seoLink: muscle.seoLink,
      muscleXMuscleGroups: muscle.muscleXMuscleGroups
    });
  },
  delete(id) {
    return axios.delete("/api/muscles/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/muscles");
  },
  findMuscle(id) {
    return axios.get("/api/muscles/"+id);
  }
};
