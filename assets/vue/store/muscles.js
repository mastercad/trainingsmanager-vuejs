import MuscleController from "../controllers/muscles";

const CREATING_MUSCLE = "CREATING_MUSCLE",
  CREATING_MUSCLE_SUCCESS = "CREATING_MUSCLE_SUCCESS",
  CREATING_MUSCLE_ERROR = "CREATING_MUSCLE_ERROR",
  UPDATE_MUSCLE = "UPDATE_MUSCLE",
  UPDATE_MUSCLE_SUCCESS = "UPDATE_MUSCLE_SUCCESS",
  UPDATE_MUSCLE_ERROR = "UPDATE_MUSCLE_ERROR",
  DELETE_MUSCLE = "DELETE_MUSCLE",
  DELETE_MUSCLE_SUCCESS = "DELETE_MUSCLE_SUCCESS",
  DELETE_MUSCLE_ERROR = "DELETE_MUSCLE_ERROR",
  FETCHING_MUSCLES = "FETCHING_MUSCLES",
  FETCHING_MUSCLES_SUCCESS = "FETCHING_MUSCLES_SUCCESS",
  FETCHING_MUSCLES_ERROR = "FETCHING_MUSCLES_ERROR",
  FETCHING_MUSCLE = "FETCHING_MUSCLE",
  FETCHING_MUSCLE_SUCCESS = "FETCHING_MUSCLE_SUCCESS",
  FETCHING_MUSCLE_ERROR = "FETCHING_MUSCLE_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    muscles: [],
    muscle: null
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
    hasMuscles(state) {
      return state.muscles.length > 0;
    },
    muscles(state) {
      return state.muscles;
    },
    muscle(state) {
      return state.muscle;
    }
  },
  mutations: {
    [CREATING_MUSCLE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_MUSCLE_SUCCESS](state, muscle) {
      state.isPanelLoading = false;
      state.error = null;
      state.muscles.push(muscle);
    },
    [CREATING_MUSCLE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscles = [];
    },
    [UPDATE_MUSCLE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_MUSCLE_SUCCESS](state, muscle) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.muscles.length; ++index) {
        if (state.muscles[index].id === muscle.id) {
          state.muscles[index] = muscle;
        }
      }
    },
    [UPDATE_MUSCLE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscles = [];
    },
    [DELETE_MUSCLE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_MUSCLE_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.muscles.findIndex(muscle => muscle.id == id);
      state.muscles.splice(index, 1);
    },
    [FETCHING_MUSCLES](state) {
      state.isLoading = true;
      state.error = null;
      state.muscles = [];
    },
    [FETCHING_MUSCLES_SUCCESS](state, muscles) {
      state.isLoading = false;
      state.error = null;
      state.muscles = muscles;
    },
    [FETCHING_MUSCLES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.muscles = [];
    },
    [FETCHING_MUSCLE](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.muscle = null;
    },
    [FETCHING_MUSCLE_SUCCESS](state, muscle) {
      state.isPanelLoading = false;
      state.error = null;
      state.muscle = muscle;
    },
    [FETCHING_MUSCLE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.muscles = [];
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_MUSCLE);
      try {
        let response = await MuscleController.create(data.name, data.name, data.seoLink, data.muscleXMuscleGroups);
        commit(CREATING_MUSCLE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_MUSCLE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_MUSCLE);
      try {
        let response = await MuscleController.update(data.id, data.name, data.seoLink, data.muscleXMuscleGroups);
        commit(UPDATE_MUSCLE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_MUSCLE_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_MUSCLE);
      try {
        let response = await MuscleController.delete(id);
        commit(DELETE_MUSCLE_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_MUSCLE_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_MUSCLES);
      try {
        let response = await MuscleController.findAll();
        commit(FETCHING_MUSCLES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_MUSCLES_ERROR, error);
        return null;
      }
    },
    async findMuscle({ commit }, id) {
      commit(FETCHING_MUSCLE);
      try {
        let response = await MuscleController.findMuscle(id);
        commit(FETCHING_MUSCLE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_MUSCLE_ERROR, error);
        return null;
      }
    }
  }
};
