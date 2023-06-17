import axios from "axios";

export default {
  create(exercise) {
    return axios.post("/api/exercises", {
      name: exercise.name,
      seoLink: exercise.seoLink,
      description: exercise.description,
      specialFeatures: exercise.specialFeatures,
      previewPicturePath: exercise.previewPicturePath,
      exerciseXDeviceOptions: exercise.exerciseXDeviceOptions,
      exerciseXExerciseOptions: exercise.exerciseXExerciseOptions,
      exerciseXDevices: exercise.exerciseXDevices,
      exerciseXExerciseType: exercise.exerciseXExerciseType
    });
  },
  update(exercise) {
    return axios.put("/api/exercises/"+exercise.id, {
      name: exercise.name,
      seoLink: exercise.seoLink,
      description: exercise.description,
      specialFeatures: exercise.specialFeatures,
      previewPicturePath: exercise.previewPicturePath,
      exerciseXDeviceOptions: exercise.exerciseXDeviceOptions,
      exerciseXExerciseOptions: exercise.exerciseXExerciseOptions,
      exerciseXDevices: exercise.exerciseXDevices,
      exerciseXExerciseType: exercise.exerciseXExerciseType
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
  deleteUploadImage(image) {
    return axios.delete("/api/uploads/image/"+image, {
    });
  },
  deleteExerciseImage(image, id) {
    return axios.delete("/api/exercises/"+id+"/image/"+image, {
    });
  }
};
