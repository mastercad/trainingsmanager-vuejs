import TrainingPlanController from "../controllers/trainingPlans.js";

const CREATING_TRAINING_PLAN = "CREATING_TRAINING_PLAN",
  CREATING_TRAINING_PLAN_SUCCESS = "CREATING_TRAINING_PLAN_SUCCESS",
  CREATING_TRAINING_PLAN_ERROR = "CREATING_TRAINING_PLAN_ERROR",
  UPDATE_TRAINING_PLAN = "UPDATE_TRAINING_PLAN",
  UPDATE_TRAINING_PLAN_SUCCESS = "UPDATE_TRAINING_PLAN_SUCCESS",
  UPDATE_TRAINING_PLAN_ERROR = "UPDATE_TRAINING_PLAN_ERROR",
  DELETE_TRAINING_PLAN = "DELETE_TRAINING_PLAN",
  DELETE_TRAINING_PLAN_SUCCESS = "DELETE_TRAINING_PLAN_SUCCESS",
  DELETE_TRAINING_PLAN_ERROR = "DELETE_TRAINING_PLAN_ERROR",
  FETCHING_TRAINING_PLANS = "FETCHING_TRAINING_PLANS",
  FETCHING_TRAINING_PLANS_SUCCESS = "FETCHING_TRAINING_PLANS_SUCCESS",
  FETCHING_TRAINING_PLANS_ERROR = "FETCHING_TRAINING_PLANS_ERROR",
  REGISTER_TRAINING_PLAN = "REGISTER_TRAINING_PLAN",
  UNREGISTER_TRAINING_PLAN = "UNREGISTER_TRAINING_PLAN";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    trainingPlans: []
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
    hasTrainingPlans(state) {
      return state.trainingPlans.length > 0;
    },
    trainingPlans(state) {
      return state.trainingPlans;
    },
    findOldRegistered(state) {
      let index = state.trainingPlans.findIndex(trainingPlan => (typeof trainingPlan.id === 'string' || trainingPlan.id instanceof String) && trainingPlan.id.startsWith('training_plan_'));

      return state.trainingPlans[index];
    }
  },
  mutations: {
    [CREATING_TRAINING_PLAN](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_TRAINING_PLAN_SUCCESS](state, trainingPlan) {
      state.isLoading = false;
      state.error = null;
      state.trainingPlans.push(trainingPlan);
    },
    [CREATING_TRAINING_PLAN_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlans = [];
    },
    [UPDATE_TRAINING_PLAN](state) {
      state.isLoading = true;
      state.error = null;
    },
    [UPDATE_TRAINING_PLAN_SUCCESS](state, trainingPlan) {
      state.isLoading = false;
      state.error = null;
      for (let index = 0; index < state.trainingPlans.length; ++index) {
        if (state.trainingPlans[index].id === trainingPlan.id) {
          state.trainingPlans[index] = trainingPlan;
        }
      }
    },
    [UPDATE_TRAINING_PLAN_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlans = [];
    },
    [DELETE_TRAINING_PLAN](state) {
      state.isLoading = true;
      state.error = null;
    },
    [DELETE_TRAINING_PLAN_SUCCESS](state, id) {
      state.isLoading = false;
      state.error = null;

      let index = state.trainingPlans.findIndex(currentTrainingPlan => currentTrainingPlan.id == id);
      state.trainingPlans.splice(index, 1);
    },
    [DELETE_TRAINING_PLAN_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlans = [];
    },
    [FETCHING_TRAINING_PLANS](state) {
      state.isLoading = true;
      state.error = null;
      state.trainingPlans = [];
    },
    [FETCHING_TRAINING_PLANS_SUCCESS](state, trainingPlans) {
      state.isLoading = false;
      state.error = null;
      state.trainingPlans = trainingPlans;
    },
    [FETCHING_TRAINING_PLANS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.trainingPlans = [];
    },
    [REGISTER_TRAINING_PLAN](state, trainingPlan) {
      state.trainingPlans.push(trainingPlan);

      return trainingPlan;
    },
    [UNREGISTER_TRAINING_PLAN](state, trainingPlan) {
      let index = state.trainingPlans.findIndex(currentTrainingPlan => (currentTrainingPlan.id === trainingPlan.id));

      return state.trainingPlans.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_TRAINING_PLAN);
      try {
        let response = await TrainingPlanController.create(data.name, data.trainingPlanLayout, data.owner, data.parent, data.active, data.order);
        commit(CREATING_TRAINING_PLAN_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_TRAINING_PLAN_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_TRAINING_PLAN);
      try {
        let response = await TrainingPlanController.update(data.id, data.name, data.trainingPlanLayout, data.owner, data.parent, data.active, data.order);
        commit(UPDATE_TRAINING_PLAN_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_TRAINING_PLAN_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_TRAINING_PLAN);
      try {
        let response = await TrainingPlanController.delete(id);
        commit(DELETE_TRAINING_PLAN_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_TRAINING_PLAN_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_TRAINING_PLANS);
      try {
        let response = await TrainingPlanController.findAll();
        commit(FETCHING_TRAINING_PLANS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_TRAINING_PLANS_ERROR, error);
        return null;
      }
    },
    async register({ commit }, trainingPlan) {
      return commit(REGISTER_TRAINING_PLAN, trainingPlan);
    },
    async unregister({ commit }, trainingPlan) {
      return commit(UNREGISTER_TRAINING_PLAN, trainingPlan);
    }
  }
};
