import TrainingPlanLayoutController from "../controllers/trainingPlanLayouts";

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
  FETCHING_TRAINING_PLAN_LAYOUTS_ERROR = "FETCHING_TRAINING_PLAN_LAYOUTS_ERROR";

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
    }
  },
  mutations: {
    [CREATING_TRAINING_PLAN_LAYOUT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_TRAINING_PLAN_LAYOUT_SUCCESS](state, trainingPlanLayout) {
      state.isLoading = false;
      state.error = null;
      //      state.trainingPlanLayouts.unshift(trainingPlanLayout);
      state.trainingPlanLayouts.push(trainingPlanLayout);
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
    [UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS](state, trainingPlanLayout) {
      state.isLoading = false;
      state.error = null;
      for (let index = 0; index < state.trainingPlanLayouts.length; ++index) {
        if (state.trainingPlanLayouts[index].id === trainingPlanLayout.id) {
          state.trainingPlanLayouts[index] = trainingPlanLayout;
        }
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
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_TRAINING_PLAN_LAYOUT);
      try {
        let response = await TrainingPlanLayoutController.create(data.name);
        commit(CREATING_TRAINING_PLAN_LAYOUT_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_TRAINING_PLAN_LAYOUT_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_TRAINING_PLAN_LAYOUT);
      try {
        let response = await TrainingPlanLayoutController.update(data.id, data.name);
        commit(UPDATE_TRAINING_PLAN_LAYOUT_SUCCESS, response.data);
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
    }
  }
};
