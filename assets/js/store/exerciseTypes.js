import ExerciseTypeController from "../controllers/exerciseTypes.js";

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
  FETCHING_EXERCISE_TYPE_ERROR = "FETCHING_EXERCISE_TYPE_ERROR",
  REGISTER_EXERCISE_TYPE = "REGISTER_EXERCISE_TYPE",
  UNREGISTER_EXERCISE_TYPE = "UNREGISTER_EXERCISE_TYPE";

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
    },
    findOldRegistered(state) {
      let index = state.exerciseTypes.findIndex(currentExerciseType => (typeof currentExerciseType.id === 'string' || currentExerciseType.id instanceof String) && currentExerciseType.id.startsWith('exercise_type_'));

      return state.exerciseTypes[index];
    }
  },
  mutations: {
    [CREATING_EXERCISE_TYPE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_EXERCISE_TYPE_SUCCESS](state, data) {
      let exerciseType = data.exerciseType;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.exerciseTypes.findIndex(currentExerciseType => currentExerciseType.id == previousId);
      if (0 <= index) {
        state.exerciseTypes[index].id = exerciseType.id;
        state.exerciseTypes[index].name = exerciseType.name;
        state.exerciseTypes[index].creator = exerciseType.creator;
        state.exerciseTypes[index].created = exerciseType.created;
        state.exerciseTypes[index].updated = exerciseType.updated;
        state.exerciseTypes[index].updater = exerciseType.updater;
      }
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
    [UPDATE_EXERCISE_TYPE_SUCCESS](state, data) {
      let exerciseType = data.exerciseType;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.exerciseTypes.findIndex(currentExerciseType => currentExerciseType.id == previousId);

      if (0 <= index) {
        state.exerciseTypes[index].id = exerciseType.id;
        state.exerciseTypes[index].name = exerciseType.name;
        state.exerciseTypes[index].creator = exerciseType.creator;
        state.exerciseTypes[index].created = exerciseType.created;
        state.exerciseTypes[index].updated = exerciseType.updated;
        state.exerciseTypes[index].updater = exerciseType.updater;
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
    },
    [REGISTER_EXERCISE_TYPE](state, exerciseType) {
      state.exerciseTypes.push(exerciseType);

      return exerciseType;
    },
    [UNREGISTER_EXERCISE_TYPE](state, exerciseType) {
      let index = state.exerciseTypes.findIndex(currentExerciseType => (currentExerciseType.id === exerciseType.id));

      return state.exerciseTypes.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, exerciseType) {
      commit(CREATING_EXERCISE_TYPE);
      try {
        const origId = exerciseType.id;
        exerciseType.id = null;
        let response = await ExerciseTypeController.create(exerciseType);
        commit(CREATING_EXERCISE_TYPE_SUCCESS, {exerciseType: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_EXERCISE_TYPE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, exerciseType) {
      commit(UPDATE_EXERCISE_TYPE);
      try {
        let response = await ExerciseTypeController.update(exerciseType);
        commit(UPDATE_EXERCISE_TYPE_SUCCESS, {exerciseType: response.data, origId: exerciseType.id});
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
    },
    async register({ commit }, exerciseType) {
      return commit(REGISTER_EXERCISE_TYPE, exerciseType);
    },
    async unregister({ commit }, exerciseType) {
      return commit(UNREGISTER_EXERCISE_TYPE, exerciseType);
    }
  }
};
