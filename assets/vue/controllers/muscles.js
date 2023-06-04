import axios from "axios";

export default {
  create(name, seoLink, muscleXMuscleGroups) {
    return axios.post("/api/muscles", {
      name: name,
      seoLink: seoLink,
      muscleXMuscleGroups: muscleXMuscleGroups
    });
  },
  update(id, name, seoLink, muscleXMuscleGroups) {
    return axios.put("/api/muscles/"+id, {
      name: name,
      seoLink: seoLink,
      muscleXMuscleGroups: muscleXMuscleGroups
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
