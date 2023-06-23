import axios from "axios";

export default {
  create(device) {
    return axios.post("/api/devices", {
      name: device.name,
      seoLink: device.seoLink,
      previewPicturePath: device.previewPicturePath,
      deviceXDeviceOptions: device.deviceXDeviceOptions,
      deviceXDeviceGroups: device.deviceXDeviceGroups
    });
  },
  update(device) {
    return axios.put("/api/devices/"+device.id, {
      name: device.name,
      seoLink: device.seoLink,
      previewPicturePath: device.previewPicturePath,
      deviceXDeviceOptions: device.deviceXDeviceOptions,
      deviceXDeviceGroups: device.deviceXDeviceGroups
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
