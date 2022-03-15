import axios from "axios";

export default {
  create(name, seoLink, description, specialFeatures, previewPicturePath) {
    return axios.post("/api/exercises", {
      name: name,
      seoLink: seoLink,
      description: description,
      specialFeatures: specialFeatures,
      previewPicturePath: previewPicturePath
    });
  },
  update(id, name, seoLink, description, specialFeatures, previewPicturePath) {
    return axios.put("/api/exercises/"+id, {
      name: name,
      seoLink: seoLink,
      description: description,
      specialFeatures: specialFeatures,
      previewPicturePath: previewPicturePath
    });
  },
  delete(id) {
    return axios.delete("/api/exercises/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/exercises");
  },
  findExercise(id) {
    return axios.get("/api/exercises/"+id);
  },
  findExerciseForEdit(id) {
    return axios.get("/api/exercises/"+id+"/1");
  },
  loadImages(id) {
    return axios.get("/api/exercises/"+id+"/images");
  },
  deleteImage(image) {
    return axios.delete("/api/exercises/image/"+image, {
    });
  }
};
