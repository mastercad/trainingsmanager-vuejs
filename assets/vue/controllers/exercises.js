import axios from "axios";

export default {
  create(name, seoLink, description, specialFeatures, previewPicturePath, exerciseXDeviceOptions, exerciseXExerciseOptions, exerciseXDevice, exerciseXExerciseType) {
    return axios.post("/api/exercises", {
      name: name,
      seoLink: seoLink,
      description: description,
      specialFeatures: specialFeatures,
      previewPicturePath: previewPicturePath,
      exerciseXDeviceOptions: exerciseXDeviceOptions,
      exerciseXExerciseOptions: exerciseXExerciseOptions,
      exerciseXDevice: exerciseXDevice,
      exerciseXExerciseType: exerciseXExerciseType
    });
  },
  update(id, name, seoLink, description, specialFeatures, previewPicturePath, exerciseXDeviceOptions, exerciseXExerciseOptions, exerciseXDevices, exerciseXExerciseType) {
    return axios.put("/api/exercises/"+id, {
      name: name,
      seoLink: seoLink,
      description: description,
      specialFeatures: specialFeatures,
      previewPicturePath: previewPicturePath,
      exerciseXDeviceOptions: exerciseXDeviceOptions,
      exerciseXExerciseOptions: exerciseXExerciseOptions,
      exerciseXDevices: exerciseXDevices,
      exerciseXExerciseType: exerciseXExerciseType
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
