import DeviceController from "../controllers/devices";

const CREATING_DEVICE = "CREATING_DEVICE",
  CREATING_DEVICE_SUCCESS = "CREATING_DEVICE_SUCCESS",
  CREATING_DEVICE_ERROR = "CREATING_DEVICE_ERROR",
  UPDATE_DEVICE = "UPDATE_DEVICE",
  UPDATE_DEVICE_SUCCESS = "UPDATE_DEVICE_SUCCESS",
  UPDATE_DEVICE_ERROR = "UPDATE_DEVICE_ERROR",
  DELETE_DEVICE = "DELETE_DEVICE",
  DELETE_DEVICE_SUCCESS = "DELETE_DEVICE_SUCCESS",
  DELETE_DEVICE_ERROR = "DELETE_DEVICE_ERROR",
  DELETE_DEVICE_IMAGE = "DELETE_DEVICE_IMAGE",
  DELETE_DEVICE_IMAGE_SUCCESS = "DELETE_DEVICE_IMAGE_SUCCESS",
  DELETE_DEVICE_IMAGE_ERROR = "DELETE_DEVICE_IMAGE_ERROR",
  DELETE_UPLOAD_IMAGE = "DELETE_UPLOAD_IMAGE",
  DELETE_UPLOAD_IMAGE_SUCCESS = "DELETE_UPLOAD_IMAGE_SUCCESS",
  DELETE_UPLOAD_IMAGE_ERROR = "DELETE_UPLOAD_IMAGE_ERROR",
  FETCHING_DEVICES = "FETCHING_DEVICES",
  FETCHING_DEVICES_SUCCESS = "FETCHING_DEVICES_SUCCESS",
  FETCHING_DEVICES_ERROR = "FETCHING_DEVICES_ERROR",
  FETCHING_DEVICE = "FETCHING_DEVICE",
  FETCHING_DEVICE_SUCCESS = "FETCHING_DEVICE_SUCCESS",
  FETCHING_DEVICE_ERROR = "FETCHING_DEVICE_ERROR",
  FETCHING_DEVICE_FOR_EDIT = "FETCHING_DEVICE_FOR_EDIT",
  FETCHING_DEVICE_FOR_EDIT_SUCCESS = "FETCHING_DEVICE_FOR_EDIT_SUCCESS",
  FETCHING_DEVICE_FOR_EDIT_ERROR = "FETCHING_DEVICE_FOR_EDIT_ERROR",
  FETCHING_DEVICE_IMAGES = "FETCHING_DEVICE_IMAGES",
  FETCHING_DEVICE_IMAGES_SUCCESS = "FETCHING_DEVICE_IMAGES_SUCCESS",
  FETCHING_DEVICE_IMAGES_ERROR = "FETCHING_DEVICE_IMAGES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isImagesLoading: false,
    isPanelLoading: false,
    error: null,
    devices: [],
    device: null,
    deviceImages: []
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    isPanelLoading(state) {
      return state.isPanelLoading;
    },
    isImagesLoading(state) {
      return state.isImagesLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasDevices(state) {
      return state.devices.length > 0;
    },
    devices(state) {
      return state.devices;
    },
    device(state) {
      return state.device;
    },
    deviceImages(state) {
      return state.deviceImages;
    }
  },
  mutations: {
    [CREATING_DEVICE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_DEVICE_SUCCESS](state, device) {
      state.isPanelLoading = false;
      state.error = null;
      state.devices.push(device);
    },
    [CREATING_DEVICE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.devices = [];
    },
    [UPDATE_DEVICE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_DEVICE_SUCCESS](state, device) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.devices.length; ++index) {
        if (state.devices[index].id === device.id) {
          state.devices[index].creator = device.creator;
          state.devices[index].created = device.created;
          state.devices[index].updated = device.updated;
          state.devices[index].updater = device.updater;
          state.devices[index].deviceXDeviceOptions = device.deviceXDeviceOptions;
          state.devices[index].name = device.name;
          state.devices[index].previewPicturePath = device.previewPicturePath;
          state.devices[index].seoLink = device.seoLink;
        }
      }
    },
    [UPDATE_DEVICE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.devices = [];
    },
    [DELETE_DEVICE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_DEVICE_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.devices.findIndex(currentDevice => currentDevice.id == id);
      state.devices.splice(index, 1);
    },
    [DELETE_DEVICE_IMAGE](state) {
      state.isImagesLoading = true;
      state.error = null;
    },
    [DELETE_DEVICE_IMAGE_SUCCESS](state) {
      state.isImagesLoading = false;
      state.error = null;
    },
    [DELETE_DEVICE_IMAGE_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
    },
    [DELETE_UPLOAD_IMAGE](state) {
      state.isImagesLoading = true;
      state.error = null;
    },
    [DELETE_UPLOAD_IMAGE_SUCCESS](state) {
      state.isImagesLoading = false;
      state.error = null;
    },
    [DELETE_UPLOAD_IMAGE_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
    },
    [FETCHING_DEVICES](state) {
      state.isLoading = true;
      state.error = null;
      state.devices = [];
    },
    [FETCHING_DEVICES_SUCCESS](state, devices) {
      state.isLoading = false;
      state.error = null;
      state.devices = devices;
    },
    [FETCHING_DEVICES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.devices = [];
    },
    [FETCHING_DEVICE](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.device = null;
    },
    [FETCHING_DEVICE_SUCCESS](state, device) {
      state.isPanelLoading = false;
      state.error = null;
      state.device = device;
    },
    [FETCHING_DEVICE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.devices = [];
    },
    [FETCHING_DEVICE_FOR_EDIT](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.device = null;
    },
    [FETCHING_DEVICE_FOR_EDIT_SUCCESS](state, device) {
      state.isPanelLoading = false;
      state.error = null;
      state.device = device;
    },
    [FETCHING_DEVICE_FOR_EDIT_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.device = null;
    },
    [FETCHING_DEVICE_IMAGES](state) {
      state.isImagesLoading = true;
      state.error = null;
      state.deviceImages = null;
    },
    [FETCHING_DEVICE_IMAGES_SUCCESS](state, images) {
      state.isImagesLoading = false;
      state.error = null;
      state.deviceImages = images;
    },
    [FETCHING_DEVICE_IMAGES_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
      state.deviceImages = null;
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_DEVICE);
      try {
        let response = await DeviceController.create(data.name, data.seoLink, data.previewPicturePath, data.deviceXDeviceOptions);
        commit(CREATING_DEVICE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_DEVICE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_DEVICE);
      try {
        let response = await DeviceController.update(data.id, data.name, data.seoLink, data.previewPicturePath, data.deviceXDeviceOptions);
        commit(UPDATE_DEVICE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_DEVICE_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_DEVICE);
      try {
        let response = await DeviceController.delete(id);
        commit(DELETE_DEVICE_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_DEVICE_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_DEVICES);
      try {
        let response = await DeviceController.findAll();
        commit(FETCHING_DEVICES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICES_ERROR, error);
        return null;
      }
    },
    async findDevice({ commit }, id) {
      commit(FETCHING_DEVICE);
      try {
        let response = await DeviceController.findDevice(id);
        commit(FETCHING_DEVICE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_ERROR, error);
        return null;
      }
    },
    async findDeviceForEdit({ commit }, id) {
      commit(FETCHING_DEVICE_FOR_EDIT);
      try {
        let response = await DeviceController.findDeviceForEdit(id);
        commit(FETCHING_DEVICE_FOR_EDIT_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_FOR_EDIT_ERROR, error);
        return null;
      }
    },
    async loadImages({ commit }, id) {
      commit(FETCHING_DEVICE_IMAGES);
      try {
        let response = await DeviceController.loadImages(id);
        commit(FETCHING_DEVICE_IMAGES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_IMAGES_ERROR, error);
        return null;
      }
    },
    async deleteUploadImage({ commit }, image) {
      commit(DELETE_UPLOAD_IMAGE);
      try {
        let response = await DeviceController.deleteUploadImage(image);
        commit(DELETE_UPLOAD_IMAGE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(DELETE_UPLOAD_IMAGE_ERROR, error);
        return null;
      }
    },
    async deleteDeviceImage({ commit }, payload) {
      commit(DELETE_DEVICE_IMAGE);
      try {
        let response = await DeviceController.deleteDeviceImage(payload.fileName, payload.id);
        commit(DELETE_DEVICE_IMAGE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(DELETE_DEVICE_IMAGE_ERROR, error);
        return null;
      }
    }
  }
};
