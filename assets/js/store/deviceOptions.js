import DeviceOptionController from "../controllers/deviceOptions.js";

const CREATING_DEVICE_OPTION = "CREATING_DEVICE_OPTION",
  CREATING_DEVICE_OPTION_SUCCESS = "CREATING_DEVICE_OPTION_SUCCESS",
  CREATING_DEVICE_OPTION_ERROR = "CREATING_DEVICE_OPTION_ERROR",
  UPDATE_DEVICE_OPTION = "UPDATE_DEVICE_OPTION",
  UPDATE_DEVICE_OPTION_SUCCESS = "UPDATE_DEVICE_OPTION_SUCCESS",
  UPDATE_DEVICE_OPTION_ERROR = "UPDATE_DEVICE_OPTION_ERROR",
  DELETE_DEVICE_OPTION = "DELETE_DEVICE_OPTION",
  DELETE_DEVICE_OPTION_SUCCESS = "DELETE_DEVICE_OPTION_SUCCESS",
  DELETE_DEVICE_OPTION_ERROR = "DELETE_DEVICE_OPTION_ERROR",
  FETCHING_DEVICE_OPTIONS = "FETCHING_DEVICE_OPTIONS",
  FETCHING_DEVICE_OPTIONS_SUCCESS = "FETCHING_DEVICE_OPTIONS_SUCCESS",
  FETCHING_DEVICE_OPTIONS_ERROR = "FETCHING_DEVICE_OPTIONS_ERROR",
  FETCHING_DEVICE_OPTION = "FETCHING_DEVICE_OPTION",
  FETCHING_DEVICE_OPTION_SUCCESS = "FETCHING_DEVICE_OPTION_SUCCESS",
  FETCHING_DEVICE_OPTION_ERROR = "FETCHING_DEVICE_OPTION_ERROR",
  REGISTER_DEVICE_OPTION = "REGISTER_DEVICE_OPTION",
  UNREGISTER_DEVICE_OPTION = "UNREGISTER_DEVICE_OPTION";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    deviceOptions: [],
    deviceOption: null
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    isPanelLoading(state) {
      return state.isPanelLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasDeviceOptions(state) {
      return state.deviceOptions.length > 0;
    },
    deviceOptions(state) {
      return state.deviceOptions;
    },
    deviceOption(state) {
      return state.deviceOption;
    },
    findOldRegistered(state) {
      let index = state.deviceOptions.findIndex(currentDeviceOption => ((typeof currentDeviceOption.id === 'string' || currentDeviceOption.id instanceof String) && currentDeviceOption.id.startsWith('device_option_')));
      return state.deviceOptions[index];
    }
  },
  mutations: {
    [CREATING_DEVICE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_DEVICE_OPTION_SUCCESS](state, data) {
      let deviceOption = data.deviceOption;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceOptions.findIndex(currentDeviceOption => currentDeviceOption.id == previousId);

      if (0 <= index) {
        state.deviceOptions[index].id = deviceOption.id;
        state.deviceOptions[index].name = deviceOption.name;
        state.deviceOptions[index].defaultValue = deviceOption.defaultValue;
        state.deviceOptions[index].creator = deviceOption.creator;
        state.deviceOptions[index].created = deviceOption.created;
        state.deviceOptions[index].updated = deviceOption.updated;
        state.deviceOptions[index].updater = deviceOption.updater;
      }
    },
    [CREATING_DEVICE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceOptions = [];
    },
    [UPDATE_DEVICE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_DEVICE_OPTION_SUCCESS](state, data) {
      let deviceOption = data.deviceOption;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceOptions.findIndex(currentDeviceOption => currentDeviceOption.id == previousId);

      if (0 <= index) {
        state.deviceOptions[index].id = deviceOption.id;
        state.deviceOptions[index].name = deviceOption.name;
        state.deviceOptions[index].defaultValue = deviceOption.defaultValue;
        state.deviceOptions[index].creator = deviceOption.creator;
        state.deviceOptions[index].created = deviceOption.created;
        state.deviceOptions[index].updated = deviceOption.updated;
        state.deviceOptions[index].updater = deviceOption.updater;
      }
    },
    [UPDATE_DEVICE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceOptions = [];
    },
    [DELETE_DEVICE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_DEVICE_OPTION_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceOptions.findIndex(currentDevice => currentDevice.id == id);
      state.deviceOptions.splice(index, 1);
    },
    [FETCHING_DEVICE_OPTIONS](state) {
      state.isLoading = true;
      state.error = null;
      state.deviceOptions = [];
    },
    [FETCHING_DEVICE_OPTIONS_SUCCESS](state, deviceOptions) {
      state.isLoading = false;
      state.error = null;
      state.deviceOptions = deviceOptions;
    },
    [FETCHING_DEVICE_OPTIONS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.deviceOptions = [];
    },
    [FETCHING_DEVICE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.deviceOption = null;
    },
    [FETCHING_DEVICE_OPTION_SUCCESS](state, deviceOption) {
      state.isPanelLoading = false;
      state.error = null;
      state.deviceOption = deviceOption;
    },
    [FETCHING_DEVICE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceOptions = [];
    },
    [REGISTER_DEVICE_OPTION](state, deviceOption) {
      state.deviceOptions.push(deviceOption);

      return deviceOption;
    },
    [UNREGISTER_DEVICE_OPTION](state, deviceOption) {
      let index = state.deviceOptions.findIndex(currentDeviceOption => (currentDeviceOption.id === deviceOption.id));

      return state.deviceOptions.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, deviceOption) {
      commit(CREATING_DEVICE_OPTION);
      try {
        const origId = deviceOption.id;
        let response = await DeviceOptionController.create(deviceOption);
        commit(CREATING_DEVICE_OPTION_SUCCESS, {deviceOption: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_DEVICE_OPTION_ERROR, error);
        return null;
      }
    },
    async update({ commit }, deviceOption) {
      commit(UPDATE_DEVICE_OPTION);
      try {
        let response = await DeviceOptionController.update(deviceOption);
        commit(UPDATE_DEVICE_OPTION_SUCCESS, {deviceOption: response.data, origId: deviceOption.id});
        return response.data;
      } catch (error) {
        commit(UPDATE_DEVICE_OPTION_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_DEVICE_OPTION);
      try {
        let response = await DeviceOptionController.delete(id);
        commit(DELETE_DEVICE_OPTION_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_DEVICE_OPTION_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_DEVICE_OPTIONS);
      try {
        let response = await DeviceOptionController.findAll();
        commit(FETCHING_DEVICE_OPTIONS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_OPTIONS_ERROR, error);
        return null;
      }
    },
    async findDeviceOption({ commit }, id) {
      commit(FETCHING_DEVICE_OPTION);
      try {
        let response = await DeviceOptionController.findDeviceOption(id);
        commit(FETCHING_DEVICE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_OPTION_ERROR, error);
        return null;
      }
    },
    async register({ commit }, deviceOption) {
      return commit(REGISTER_DEVICE_OPTION, deviceOption);
    },
    async unregister({ commit }, deviceOption) {
      return commit(UNREGISTER_DEVICE_OPTION, deviceOption);
    }
  }
};
