import ExerciseOptionController from "../controllers/exerciseOptions";

const CREATING_EXERCISE_OPTION = "CREATING_EXERCISE_OPTION",
  CREATING_EXERCISE_OPTION_SUCCESS = "CREATING_EXERCISE_OPTION_SUCCESS",
  CREATING_EXERCISE_OPTION_ERROR = "CREATING_EXERCISE_OPTION_ERROR",
  UPDATE_EXERCISE_OPTION = "UPDATE_EXERCISE_OPTION",
  UPDATE_EXERCISE_OPTION_SUCCESS = "UPDATE_EXERCISE_OPTION_SUCCESS",
  UPDATE_EXERCISE_OPTION_ERROR = "UPDATE_EXERCISE_OPTION_ERROR",
  DELETE_EXERCISE_OPTION = "DELETE_EXERCISE_OPTION",
  DELETE_EXERCISE_OPTION_SUCCESS = "DELETE_EXERCISE_OPTION_SUCCESS",
  DELETE_EXERCISE_OPTION_ERROR = "DELETE_EXERCISE_OPTION_ERROR",
  FETCHING_EXERCISE_OPTIONS = "FETCHING_EXERCISE_OPTIONS",
  FETCHING_EXERCISE_OPTIONS_SUCCESS = "FETCHING_EXERCISE_OPTIONS_SUCCESS",
  FETCHING_EXERCISE_OPTIONS_ERROR = "FETCHING_EXERCISE_OPTIONS_ERROR",
  FETCHING_EXERCISE_OPTION = "FETCHING_EXERCISE_OPTION",
  FETCHING_EXERCISE_OPTION_SUCCESS = "FETCHING_EXERCISE_OPTION_SUCCESS",
  FETCHING_EXERCISE_OPTION_ERROR = "FETCHING_EXERCISE_OPTION_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isPanelLoading: false,
    error: null,
    exerciseOptions: [],
    exerciseOption: null
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
    hasExerciseOptions(state) {
      return state.exerciseOptions.length > 0;
    },
    exerciseOptions(state) {
      return state.exerciseOptions;
    },
    exerciseOption(state) {
      return state.exerciseOption;
    }
  },
  mutations: {
    [CREATING_EXERCISE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_EXERCISE_OPTION_SUCCESS](state, exerciseOption) {
      state.isPanelLoading = false;
      state.error = null;
      state.exerciseOptions.push(exerciseOption);
    },
    [CREATING_EXERCISE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseOptions = [];
    },
    [UPDATE_EXERCISE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_EXERCISE_OPTION_SUCCESS](state, exerciseOption) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.exerciseOptions.length; ++index) {
        if (state.exerciseOptions[index].id === exerciseOption.id) {
          state.exerciseOptions[index] = exerciseOption;
        }
      }
    },
    [UPDATE_EXERCISE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseOptions = [];
    },
    [DELETE_EXERCISE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_EXERCISE_OPTION_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.exerciseOptions.findIndex(currentExercise => currentExercise.id == id);
      state.exerciseOptions.splice(index, 1);
    },
    [FETCHING_EXERCISE_OPTIONS](state) {
      state.isLoading = true;
      state.error = null;
      state.exerciseOptions = [];
    },
    [FETCHING_EXERCISE_OPTIONS_SUCCESS](state, exerciseOptions) {
      state.isLoading = false;
      state.error = null;
      state.exerciseOptions = exerciseOptions;
    },
    [FETCHING_EXERCISE_OPTIONS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.exerciseOptions = [];
    },
    [FETCHING_EXERCISE_OPTION](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.exerciseOption = null;
    },
    [FETCHING_EXERCISE_OPTION_SUCCESS](state, exerciseOption) {
      state.isPanelLoading = false;
      state.error = null;
      state.exerciseOption = exerciseOption;
    },
    [FETCHING_EXERCISE_OPTION_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exerciseOptions = [];
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_EXERCISE_OPTION);
      try {
        let response = await ExerciseOptionController.create(data.name, data.defaultValue);
        commit(CREATING_EXERCISE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_EXERCISE_OPTION_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_EXERCISE_OPTION);
      try {
        let response = await ExerciseOptionController.update(data.id, data.name, data.defaultValue);
        commit(UPDATE_EXERCISE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_EXERCISE_OPTION_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_EXERCISE_OPTION);
      try {
        let response = await ExerciseOptionController.delete(id);
        commit(DELETE_EXERCISE_OPTION_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_EXERCISE_OPTION_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_EXERCISE_OPTIONS);
      try {
        let response = await ExerciseOptionController.findAll();
        commit(FETCHING_EXERCISE_OPTIONS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_OPTIONS_ERROR, error);
        return null;
      }
    },
    async findExerciseOption({ commit }, id) {
      commit(FETCHING_EXERCISE_OPTION);
      try {
        let response = await ExerciseOptionController.findExerciseOption(id);
        commit(FETCHING_EXERCISE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_OPTION_ERROR, error);
        return null;
      }
    }
  }
};
