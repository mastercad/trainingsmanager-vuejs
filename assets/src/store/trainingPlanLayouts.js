import TrainingPlanLayoutController from "../controllers/trainingPlanLayouts.js";

const CREATING_TRAINING_PLAN_LAYOUT = "CREATING_TRAINING_PLAN_LAYOUT",
  CREATING_TRAINING_PLAN_LAYOUT_SUCCESS = "CREATING_TRAINING_PLAN_LAYOUT_SUCCESS",
  CREATING_TRAINING_PLAN_LAYOUT_ERROR = "CREATING_TRAINING_PLAN_LAYOUT_ERROR",
  UPDATE_TRAINING_PLAN_LAYOUT = "UPDATE_TRAINING_PLAN_LAYOUT",
  UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS = "UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS",
  UPDATE_TRAINING_PLAN_LAYOUT_ERROR = "UPDATE_TRAINING_PLAN_LAYOUT_ERROR",
  DELETE_TRAINING_PLAN_LAYOUT = "DELETE_TRAINING_PLAN_LAYOUT",
  DELETE_TRAINING_PLAN_LAYOUT_SUCCESS = "DELETE_TRAINING_PLAN_LAYOUT_SUCCESS",
  DELETE_TRAINING_PLAN_LAYOUT_ERROR = "DELETE_TRAINING_PLAN_LAYOUT_ERROR",
  FETCHING_TRAINING_PLAN_LAYOUTS = "FETCHING_TRAINING_PLAN_LAYOUTS",
  FETCHING_TRAINING_PLAN_LAYOUTS_SUCCESS = "FETCHING_TRAINING_PLAN_LAYOUTS_SUCCESS",
  FETCHING_TRAINING_PLAN_LAYOUTS_ERROR = "FETCHING_TRAINING_PLAN_LAYOUTS_ERROR",
  REGISTER_TRAINING_PLAN_LAYOUT = "REGISTER_TRAINING_PLAN_LAYOUTS",
  UNREGISTER_TRAINING_PLAN_LAYOUT = "UNREGISTER_TRAINING_PLAN_LAYOUTS";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    trainingPlanLayouts: []
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasTrainingPlanLayouts(state) {
      return state.trainingPlanLayouts.length > 0;
    },
    trainingPlanLayouts(state) {
      return state.trainingPlanLayouts;
    },
    findOldRegistered(state) {
      let index = state.trainingPlanLayouts.findIndex(trainingPlanLayout => (typeof trainingPlanLayout.id === 'string' || trainingPlanLayout.id instanceof String) && trainingPlanLayout.id.startsWith('training_plan_layout_'));

      return state.trainingPlanLayouts[index];
    }
  },
  mutations: {
    [CREATING_TRAINING_PLAN_LAYOUT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_TRAINING_PLAN_LAYOUT_SUCCESS](state, data) {
      let trainingPlanLayout = data.trainingPlanLayout;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.trainingPlanLayouts.findIndex(currentTrainingPlanLayout => currentTrainingPlanLayout.id == previousId);

      if (0 <= index) {
        state.trainingPlanLayouts[index].id = trainingPlanLayout.id;
        state.trainingPlanLayouts[index].creator = trainingPlanLayout.creator;
        state.trainingPlanLayouts[index].created = trainingPlanLayout.created;
        state.trainingPlanLayouts[index].updated = trainingPlanLayout.updated;
        state.trainingPlanLayouts[index].updater = trainingPlanLayout.updater;
        state.trainingPlanLayouts[index].name = trainingPlanLayout.name;
      }
    },
    [CREATING_TRAINING_PLAN_LAYOUT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlanLayouts = [];
    },
    [UPDATE_TRAINING_PLAN_LAYOUT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS](state, data) {
      let trainingPlanLayout = data.trainingPlanLayout;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.trainingPlanLayouts.findIndex(currentTrainingPlanLayout => currentTrainingPlanLayout.id == previousId);

      if (0 <= index) {
        state.trainingPlanLayouts[index].id = trainingPlanLayout.id;
        state.trainingPlanLayouts[index].creator = trainingPlanLayout.creator;
        state.trainingPlanLayouts[index].created = trainingPlanLayout.created;
        state.trainingPlanLayouts[index].updated = trainingPlanLayout.updated;
        state.trainingPlanLayouts[index].updater = trainingPlanLayout.updater;
        state.trainingPlanLayouts[index].name = trainingPlanLayout.name;
      }
    },
    [UPDATE_TRAINING_PLAN_LAYOUT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlanLayouts = [];
    },
    [DELETE_TRAINING_PLAN_LAYOUT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [DELETE_TRAINING_PLAN_LAYOUT_SUCCESS](state, id) {
      state.isLoading = false;
      state.error = null;

      let index = state.trainingPlanLayouts.findIndex(currentTrainingPlanLayout => currentTrainingPlanLayout.id == id);
      state.trainingPlanLayouts.splice(index, 1);
    },
    [DELETE_TRAINING_PLAN_LAYOUT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlanLayouts = [];
    },
    [FETCHING_TRAINING_PLAN_LAYOUTS](state) {
      state.isLoading = true;
      state.error = null;
      state.trainingPlanLayouts = [];
    },
    [FETCHING_TRAINING_PLAN_LAYOUTS_SUCCESS](state, trainingPlanLayouts) {
      state.isLoading = false;
      state.error = null;
      state.trainingPlanLayouts = trainingPlanLayouts;
    },
    [FETCHING_TRAINING_PLAN_LAYOUTS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlanLayouts = [];
    },
    [REGISTER_TRAINING_PLAN_LAYOUT](state, trainingPlanLayout) {
      state.trainingPlanLayouts.push(trainingPlanLayout);

      return trainingPlanLayout;
    },
    [UNREGISTER_TRAINING_PLAN_LAYOUT](state, trainingPlanLayout) {
      let index = state.trainingPlanLayouts.findIndex(currentTrainingPlanLayout => (currentTrainingPlanLayout.id === trainingPlanLayout.id));

      return state.trainingPlanLayouts.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, trainingPlanLayout) {
      commit(CREATING_TRAINING_PLAN_LAYOUT);
      try {
        const origId = trainingPlanLayout.id; // expect the current id is autogenerated by frontend and leads in problems during save process. but need to persist to replace current entry with response
        trainingPlanLayout.id = null;
        let response = await TrainingPlanLayoutController.create(trainingPlanLayout);
        commit(CREATING_TRAINING_PLAN_LAYOUT_SUCCESS, {trainingPlanLayout: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_TRAINING_PLAN_LAYOUT_ERROR, error);
        return null;
      }
    },
    async update({ commit }, trainingPlanLayout) {
      commit(UPDATE_TRAINING_PLAN_LAYOUT);
      try {
        let response = await TrainingPlanLayoutController.update(trainingPlanLayout);
        commit(UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS, {trainingPlanLayout: response.data, origId: trainingPlanLayout.id});
        return response.data;
      } catch (error) {
        commit(UPDATE_TRAINING_PLAN_LAYOUT_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_TRAINING_PLAN_LAYOUT);
      try {
        let response = await TrainingPlanLayoutController.delete(id);
        commit(DELETE_TRAINING_PLAN_LAYOUT_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_TRAINING_PLAN_LAYOUT_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_TRAINING_PLAN_LAYOUTS);
      try {
        let response = await TrainingPlanLayoutController.findAll();
        commit(FETCHING_TRAINING_PLAN_LAYOUTS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_TRAINING_PLAN_LAYOUTS_ERROR, error);
        return null;
      }
    },
    async register({ commit }, trainingPlanLayout) {
      return commit(REGISTER_TRAINING_PLAN_LAYOUT, trainingPlanLayout);
    },
    async unregister({ commit }, trainingPlanLayout) {
      return commit(UNREGISTER_TRAINING_PLAN_LAYOUT, trainingPlanLayout);
    }
  }
};
