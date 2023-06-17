import DeviceGroupController from "../controllers/deviceGroups.js";

const CREATING_DEVICE_GROUP = "CREATING_DEVICE_GROUP",
  CREATING_DEVICE_GROUP_SUCCESS = "CREATING_DEVICE_GROUP_SUCCESS",
  CREATING_DEVICE_GROUP_ERROR = "CREATING_DEVICE_GROUP_ERROR",
  UPDATE_DEVICE_GROUP = "UPDATE_DEVICE_GROUP",
  UPDATE_DEVICE_GROUP_SUCCESS = "UPDATE_DEVICE_GROUP_SUCCESS",
  UPDATE_DEVICE_GROUP_ERROR = "UPDATE_DEVICE_GROUP_ERROR",
  DELETE_DEVICE_GROUP = "DELETE_DEVICE_GROUP",
  DELETE_DEVICE_GROUP_SUCCESS = "DELETE_DEVICE_GROUP_SUCCESS",
  DELETE_DEVICE_GROUP_ERROR = "DELETE_DEVICE_GROUP_ERROR",
  FETCHING_DEVICE_GROUPS = "FETCHING_DEVICE_GROUPS",
  FETCHING_DEVICE_GROUPS_SUCCESS = "FETCHING_DEVICE_GROUPS_SUCCESS",
  FETCHING_DEVICE_GROUPS_ERROR = "FETCHING_DEVICE_GROUPS_ERROR",
  FETCHING_DEVICE_GROUP = "FETCHING_DEVICE_GROUP",
  FETCHING_DEVICE_GROUP_SUCCESS = "FETCHING_DEVICE_GROUP_SUCCESS",
  FETCHING_DEVICE_GROUP_ERROR = "FETCHING_DEVICE_GROUP_ERROR",
  REGISTER_DEVICE_GROUP = "REGISTER_DEVICE_GROUP",
  UNREGISTER_DEVICE_GROUP = "UNREGISTER_DEVICE_GROUP";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    deviceGroups: [],
    deviceGroup: null
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
    hasDeviceGroups(state) {
      return state.deviceGroups.length > 0;
    },
    deviceGroups(state) {
      return state.deviceGroups;
    },
    deviceGroup(state) {
      return state.deviceGroup;
    },
    findOldRegistered(state) {
      let index = state.deviceGroups.findIndex(currentDeviceGroup => (typeof currentDeviceGroup.id === 'string' || currentDeviceGroup.id instanceof String) && currentDeviceGroup.id.startsWith('device_group_'));

      return state.deviceGroups[index];
    }
  },
  mutations: {
    [CREATING_DEVICE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_DEVICE_GROUP_SUCCESS](state, data) {
      let deviceGroup = data.deviceGroup;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceGroups.findIndex(currentDeviceGroup => currentDeviceGroup.id == previousId);

      if (0 <= index) {
        state.deviceGroups[index].id = deviceGroup.id;
        state.deviceGroups[index].name = deviceGroup.name;
        state.deviceGroups[index].seoLink = deviceGroup.seoLink;
        state.deviceGroups[index].creator = deviceGroup.creator;
        state.deviceGroups[index].created = deviceGroup.created;
        state.deviceGroups[index].updated = deviceGroup.updated;
        state.deviceGroups[index].updater = deviceGroup.updater;
      }
    },
    [CREATING_DEVICE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceGroups = [];
    },
    [UPDATE_DEVICE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_DEVICE_GROUP_SUCCESS](state, data) {
      let deviceGroup = data.deviceGroup;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceGroups.findIndex(currentDeviceGroup => currentDeviceGroup.id == previousId);

      if (0 <= index) {
        state.deviceGroups[index].id = deviceGroup.id;
        state.deviceGroups[index].name = deviceGroup.name;
        state.deviceGroups[index].seoLink = deviceGroup.seoLink;
        state.deviceGroups[index].creator = deviceGroup.creator;
        state.deviceGroups[index].created = deviceGroup.created;
        state.deviceGroups[index].updated = deviceGroup.updated;
        state.deviceGroups[index].updater = deviceGroup.updater;
      }
    },
    [UPDATE_DEVICE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceGroups = [];
    },
    [DELETE_DEVICE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_DEVICE_GROUP_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.deviceGroups.findIndex(currentDeviceGroup => currentDeviceGroup.id == id);
      state.deviceGroups.splice(index, 1);
    },
    [FETCHING_DEVICE_GROUPS](state) {
      state.isLoading = true;
      state.error = null;
      state.deviceGroups = [];
    },
    [FETCHING_DEVICE_GROUPS_SUCCESS](state, deviceGroups) {
      state.isLoading = false;
      state.error = null;
      state.deviceGroups = deviceGroups;
    },
    [FETCHING_DEVICE_GROUPS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.deviceGroups = [];
    },
    [FETCHING_DEVICE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.deviceGroup = null;
    },
    [FETCHING_DEVICE_GROUP_SUCCESS](state, deviceGroup) {
      state.isPanelLoading = false;
      state.error = null;
      state.deviceGroup = deviceGroup;
    },
    [FETCHING_DEVICE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.deviceGroups = [];
    },
    [REGISTER_DEVICE_GROUP](state, deviceGroup) {
      state.deviceGroups.push(deviceGroup);

      return deviceGroup;
    },
    [UNREGISTER_DEVICE_GROUP](state, deviceGroup) {
      let index = state.deviceGroups.findIndex(currentDeviceGroup => (currentDeviceGroup.id === deviceGroup.id));

      return state.deviceGroups.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, deviceGroup) {
      commit(CREATING_DEVICE_GROUP);
      try {
        const origId = deviceGroup.id;
        let response = await DeviceGroupController.create(deviceGroup);
        commit(CREATING_DEVICE_GROUP_SUCCESS, {deviceGroup: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_DEVICE_GROUP_ERROR, error);
        return null;
      }
    },
    async update({ commit }, deviceGroup) {
      commit(UPDATE_DEVICE_GROUP);
      try {
        let response = await DeviceGroupController.update(deviceGroup);
        commit(UPDATE_DEVICE_GROUP_SUCCESS, {deviceGroup: response.data, origId: deviceGroup.id});
        return response.data;
      } catch (error) {
        commit(UPDATE_DEVICE_GROUP_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_DEVICE_GROUP);
      try {
        let response = await DeviceGroupController.delete(id);
        commit(DELETE_DEVICE_GROUP_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_DEVICE_GROUP_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_DEVICE_GROUPS);
      try {
        let response = await DeviceGroupController.findAll();
        commit(FETCHING_DEVICE_GROUPS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_GROUPS_ERROR, error);
        return null;
      }
    },
    async findDeviceGroup({ commit }, id) {
      commit(FETCHING_DEVICE_GROUP);
      try {
        let response = await DeviceGroupController.findDeviceGroup(id);
        commit(FETCHING_DEVICE_GROUP_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_DEVICE_GROUP_ERROR, error);
        return null;
      }
    },
    async register({ commit }, deviceGroup) {
      return commit(REGISTER_DEVICE_GROUP, deviceGroup);
    },
    async unregister({ commit }, deviceGroup) {
      return commit(UNREGISTER_DEVICE_GROUP, deviceGroup);
    }
  }
};
