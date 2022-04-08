import axios from "axios";

export default {
  create(name, seoLink, previewPicturePath) {
    return axios.post("/api/devices", {
      name: name,
      seoLink: seoLink,
      previewPicturePath: previewPicturePath
    });
  },
  update(id, name, seoLink, previewPicturePath) {
    return axios.put("/api/devices/"+id, {
      name: name,
      seoLink: seoLink,
      previewPicturePath: previewPicturePath
    });
  },
  delete(id) {
    return axios.delete("/api/devices/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/devices");
  },
  findDevice(id) {
    return axios.get("/api/devices/"+id);
  },
  findDeviceForEdit(id) {
    return axios.get("/api/devices/"+id+"/1");
  },
  loadImages(id) {
    return axios.get("/api/devices/"+id+"/images");
  },
  deleteUploadImage(image) {
    return axios.delete("/api/uploads/image/"+image, {
    });
  },
  deleteDeviceImage(image, id) {
    return axios.delete("/api/devices/"+id+"/image/"+image, {
    });
  }
};
