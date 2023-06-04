import ExerciseTypeController from "../controllers/exerciseTypes";

const CREATING_EXERCISE_TYPE = "CREATING_EXERCISE_TYPE",
  CREATING_EXERCISE_TYPE_SUCCESS = "CREATING_EXERCISE_TYPE_SUCCESS",
  CREATING_EXERCISE_TYPE_ERROR = "CREATING_EXERCISE_TYPE_ERROR",
  UPDATE_EXERCISE_TYPE = "UPDATE_EXERCISE_TYPE",
  UPDATE_EXERCISE_TYPE_SUCCESS = "UPDATE_EXERCISE_TYPE_SUCCESS",
  UPDATE_EXERCISE_TYPE_ERROR = "UPDATE_EXERCISE_TYPE_ERROR",
  DELETE_EXERCISE_TYPE = "DELETE_EXERCISE_TYPE",
  DELETE_EXERCISE_TYPE_SUCCESS = "DELETE_EXERCISE_TYPE_SUCCESS",
  DELETE_EXERCISE_TYPE_ERROR = "DELETE_EXERCISE_TYPE_ERROR",
  FETCHING_EXERCISE_TYPES = "FETCHING_EXERCISE_TYPES",
  FETCHING_EXERCISE_TYPES_SUCCESS = "FETCHING_EXERCISE_TYPES_SUCCESS",
  FETCHING_EXERCISE_TYPES_ERROR = "FETCHING_EXERCISE_TYPES_ERROR",
  FETCHING_EXERCISE_TYPE = "FETCHING_EXERCISE_TYPE",
  FETCHING_EXERCISE_TYPE_SUCCESS = "FETCHING_EXERCISE_TYPE_SUCCESS",
  FETCHING_EXERCISE_TYPE_ERROR = "FETCHING_EXERCISE_TYPE_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    exerciseTypes: [],
    exerciseType: null
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
    hasExerciseTypes(state) {
      return state.exerciseTypes.length > 0;
    },
    exerciseTypes(state) {
      return state.exerciseTypes;
    },
    exerciseType(state) {
      return state.exerciseType;
    }
  },
  mutations: {
    [CREATING_EXERCISE_TYPE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_EXERCISE_TYPE_SUCCESS](state, exerciseType) {
      state.isPanelLoading = false;
      state.error = null;
      state.exerciseTypes.push(exerciseType);
    },
    [CREATING_EXERCISE_TYPE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseTypes = [];
    },
    [UPDATE_EXERCISE_TYPE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_EXERCISE_TYPE_SUCCESS](state, exerciseType) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.exerciseTypes.length; ++index) {
        if (state.exerciseTypes[index].id === exerciseType.id) {
          state.exerciseTypes[index] = exerciseType;
        }
      }
    },
    [UPDATE_EXERCISE_TYPE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseTypes = [];
    },
    [DELETE_EXERCISE_TYPE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_EXERCISE_TYPE_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.exerciseTypes.findIndex(currentExerciseType => currentExerciseType.id == id);
      state.exerciseTypes.splice(index, 1);
    },
    [FETCHING_EXERCISE_TYPES](state) {
      state.isLoading = true;
      state.error = null;
      state.exerciseTypes = [];
    },
    [FETCHING_EXERCISE_TYPES_SUCCESS](state, exerciseTypes) {
      state.isLoading = false;
      state.error = null;
      state.exerciseTypes = exerciseTypes;
    },
    [FETCHING_EXERCISE_TYPES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.exerciseTypes = [];
    },
    [FETCHING_EXERCISE_TYPE](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.exerciseType = null;
    },
    [FETCHING_EXERCISE_TYPE_SUCCESS](state, exerciseType) {
      state.isPanelLoading = false;
      state.error = null;
      state.exerciseType = exerciseType;
    },
    [FETCHING_EXERCISE_TYPE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseTypes = [];
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_EXERCISE_TYPE);
      try {
        let response = await ExerciseTypeController.create(data.name, data.defaultValue);
        commit(CREATING_EXERCISE_TYPE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_EXERCISE_TYPE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_EXERCISE_TYPE);
      try {
        let response = await ExerciseTypeController.update(data.id, data.name, data.defaultValue);
        commit(UPDATE_EXERCISE_TYPE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_EXERCISE_TYPE_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_EXERCISE_TYPE);
      try {
        let response = await ExerciseTypeController.delete(id);
        commit(DELETE_EXERCISE_TYPE_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_EXERCISE_TYPE_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_EXERCISE_TYPES);
      try {
        let response = await ExerciseTypeController.findAll();
        commit(FETCHING_EXERCISE_TYPES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_TYPES_ERROR, error);
        return null;
      }
    },
    async findExerciseType({ commit }, id) {
      commit(FETCHING_EXERCISE_TYPE);
      try {
        let response = await ExerciseTypeController.findExerciseType(id);
        commit(FETCHING_EXERCISE_TYPE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_TYPE_ERROR, error);
        return null;
      }
    }
  }
};
