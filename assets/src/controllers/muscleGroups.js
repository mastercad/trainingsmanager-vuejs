import axios from "axios";

export default {
  create(muscleGroup) {
    return axios.post("/api/muscle_groups", {
      name: muscleGroup.name,
      seoLink: muscleGroup.seoLink,
      color: muscleGroup.color
    });
  },
  update(muscleGroup) {
    return axios.put("/api/muscle_groups/"+muscleGroup.id, {
      name: muscleGroup.name,
      seoLink: muscleGroup.seoLink,
      color: muscleGroup.color
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
