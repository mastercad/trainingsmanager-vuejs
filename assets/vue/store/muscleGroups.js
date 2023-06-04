import MuscleGroupController from "../controllers/muscleGroups";

const CREATING_MUSCLE_GROUP = "CREATING_MUSCLE_GROUP",
  CREATING_MUSCLE_GROUP_SUCCESS = "CREATING_MUSCLE_GROUP_SUCCESS",
  CREATING_MUSCLE_GROUP_ERROR = "CREATING_MUSCLE_GROUP_ERROR",
  UPDATE_MUSCLE_GROUP = "UPDATE_MUSCLE_GROUP",
  UPDATE_MUSCLE_GROUP_SUCCESS = "UPDATE_MUSCLE_GROUP_SUCCESS",
  UPDATE_MUSCLE_GROUP_ERROR = "UPDATE_MUSCLE_GROUP_ERROR",
  DELETE_MUSCLE_GROUP = "DELETE_MUSCLE_GROUP",
  DELETE_MUSCLE_GROUP_SUCCESS = "DELETE_MUSCLE_GROUP_SUCCESS",
  DELETE_MUSCLE_GROUP_ERROR = "DELETE_MUSCLE_GROUP_ERROR",
  FETCHING_MUSCLE_GROUPS = "FETCHING_MUSCLE_GROUPS",
  FETCHING_MUSCLE_GROUPS_SUCCESS = "FETCHING_MUSCLE_GROUPS_SUCCESS",
  FETCHING_MUSCLE_GROUPS_ERROR = "FETCHING_MUSCLE_GROUPS_ERROR",
  FETCHING_MUSCLE_GROUP = "FETCHING_MUSCLE_GROUP",
  FETCHING_MUSCLE_GROUP_SUCCESS = "FETCHING_MUSCLE_GROUP_SUCCESS",
  FETCHING_MUSCLE_GROUP_ERROR = "FETCHING_MUSCLE_GROUP_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    muscleGroups: [],
    muscleGroup: null
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
    hasMuscleGroups(state) {
      return state.muscleGroups.length > 0;
    },
    muscleGroups(state) {
      return state.muscleGroups;
    },
    muscleGroup(state) {
      return state.muscleGroup;
    }
  },
  mutations: {
    [CREATING_MUSCLE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_MUSCLE_GROUP_SUCCESS](state, muscleGroup) {
      state.isPanelLoading = false;
      state.error = null;
      state.muscleGroups.push(muscleGroup);
    },
    [CREATING_MUSCLE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscleGroups = [];
    },
    [UPDATE_MUSCLE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_MUSCLE_GROUP_SUCCESS](state, muscleGroup) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.muscleGroups.length; ++index) {
        if (state.muscleGroups[index].id === muscleGroup.id) {
          state.muscleGroups[index] = muscleGroup;
        }
      }
    },
    [UPDATE_MUSCLE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscleGroups = [];
    },
    [DELETE_MUSCLE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_MUSCLE_GROUP_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.muscleGroups.findIndex(muscleGroup => muscleGroup.id == id);
      state.muscleGroups.splice(index, 1);
    },
    [FETCHING_MUSCLE_GROUPS](state) {
      state.isLoading = true;
      state.error = null;
      state.muscleGroups = [];
    },
    [FETCHING_MUSCLE_GROUPS_SUCCESS](state, muscleGroups) {
      state.isLoading = false;
      state.error = null;
      state.muscleGroups = muscleGroups;
    },
    [FETCHING_MUSCLE_GROUPS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.muscleGroups = [];
    },
    [FETCHING_MUSCLE_GROUP](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.muscleGroup = null;
    },
    [FETCHING_MUSCLE_GROUP_SUCCESS](state, muscleGroup) {
      state.isPanelLoading = false;
      state.error = null;
      state.muscleGroup = muscleGroup;
    },
    [FETCHING_MUSCLE_GROUP_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscleGroups = [];
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_MUSCLE_GROUP);
      try {
        let response = await MuscleGroupController.create(data.name, data.name, data.seoLink, data.color);
        commit(CREATING_MUSCLE_GROUP_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_MUSCLE_GROUP_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_MUSCLE_GROUP);
      try {
        let response = await MuscleGroupController.update(data.id, data.name, data.seoLink, data.color);
        commit(UPDATE_MUSCLE_GROUP_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_MUSCLE_GROUP_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_MUSCLE_GROUP);
      try {
        let response = await MuscleGroupController.delete(id);
        commit(DELETE_MUSCLE_GROUP_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_MUSCLE_GROUP_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_MUSCLE_GROUPS);
      try {
        let response = await MuscleGroupController.findAll();
        commit(FETCHING_MUSCLE_GROUPS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_MUSCLE_GROUPS_ERROR, error);
        return null;
      }
    },
    async findMuscleGroup({ commit }, id) {
      commit(FETCHING_MUSCLE_GROUP);
      try {
        let response = await MuscleGroupController.findMuscleGroup(id);
        commit(FETCHING_MUSCLE_GROUP_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_MUSCLE_GROUP_ERROR, error);
        return null;
      }
    }
  }
};
