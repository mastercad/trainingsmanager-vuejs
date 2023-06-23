import TrainingPlanExerciseOptionsController from "../controllers/trainingPlanExerciseOption.js";

const CREATING_TRAINING_PLAN_EXERCISE_OPTION = "CREATING_TRAINING_PLAN_EXERCISE_OPTION",
  CREATING_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS = "CREATING_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS",
  CREATING_TRAINING_PLAN_EXERCISE_OPTION_FAILURE = "CREATING_TRAINING_PLAN_EXERCISE_OPTION_FAILURE",
  INIT_EXERCISE_OPTIONS = "INIT_EXERCISE_OPTIONS",
  INIT_DEVICE_OPTIONS = "INIT_DEVICE_OPTIONS",
  UPDATE_TRAINING_PLAN_EXERCISE_OPTION = "UPDATE_TRAINING_PLAN_EXERCISE_OPTION",
  UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_OPTION = "UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_OPTION",
  UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_DEVICE_OPTION = "UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_DEVICE_OPTION",
  UPDATE_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS = "UPDATE_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS",
  UPDATE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE = "UPDATE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE",
  DELETE_TRAINING_PLAN_EXERCISE_OPTION = "DELETE_TRAINING_PLAN_EXERCISE_OPTION",
  DELETE_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS = "DELETE_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS",
  DELETE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE = "DELETE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE",
  UPDATE_TRAINING_PLAN_DEVICE_OPTION = "UPDATE_TRAINING_PLAN_DEVICE_OPTION",
  UPDATE_TRAINING_PLAN_DEVICE_OPTION_SUCCESS = "UPDATE_TRAINING_PLAN_DEVICE_OPTION_SUCCESS",
  UPDATE_TRAINING_PLAN_DEVICE_OPTION_FAILURE = "UPDATE_TRAINING_PLAN_DEVICE_OPTION_FAILURE",
  FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS_KEY = "FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS_KEY",
  FETCHING_TRAINING_PLAN_DEVICE_OPTIONS_KEY = "FETCHING_TRAINING_PLAN_DEVICE_OPTIONS_KEY",
  FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS = "FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    exerciseOptionKey: 'UNDEFINED',
    deviceOptionKey: 'UNDEFINED',
    selectedTrainingPlanExerciseOptions: {},
    selectedTrainingPlanDeviceOptions: {}
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
      return state.selectedTrainingPlanExerciseOptions.length > 0;
    },
    getSelectedTrainingPlanExerciseOptions(state) {
      return state.selectedTrainingPlanExerciseOptions;
    },
    getSelectedTrainingPlanExerciseDeviceOptions(state) {
      return state.selectedTrainingPlanDeviceOptions;
    },
    exerciseOptionKey(state) {
      return state.exerciseOptionKey;
    },
    deviceOptionKey(state) {
      return state.deviceOptionKey;
    }
  },
  mutations: {
    [CREATING_TRAINING_PLAN_EXERCISE_OPTION](state) {
      state.isLoading = true;
      state.error = null;
      state.selectedTrainingPlanExerciseOptions = {};
    },
    [UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_OPTION](state, data) {
      state.isLoading = true;
      state.error = null;
      if (undefined === state.selectedTrainingPlanExerciseOptions[data.id]) {
        state.selectedTrainingPlanExerciseOptions[data.id] = {};
      }
      state.selectedTrainingPlanExerciseOptions[data.id].id = data.id;
      state.selectedTrainingPlanExerciseOptions[data.id].name = data.name;
      state.selectedTrainingPlanExerciseOptions[data.id].trainingPlanXExerciseOption = data.trainingPlanXExerciseOption;
      state.selectedTrainingPlanExerciseOptions[data.id].value = data.value;
      state.selectedTrainingPlanExerciseOptions[data.id].defaultValue = data.defaultValue;
      state.selectedTrainingPlanExerciseOptions[data.id].isDefault = data.isDefault;
      state.exerciseOptionKey = Math.random();
    },
    [UPDATE_TRAINING_PLAN_EXERCISE_OPTION](state) {
      state.isLoading = true;
      state.error = null;
    },
    [UPDATE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE](state, error) {
      state.isLoading = false;
      state.error = error;
    },
    [DELETE_TRAINING_PLAN_EXERCISE_OPTION](state) {
      state.isLoading = true;
      state.error = null;
    },
    [INIT_EXERCISE_OPTIONS](state, selectedExerciseOptions) {
      state.isLoading = true;
      state.error = null;
      state.selectedTrainingPlanExerciseOptions = selectedExerciseOptions;
    },
    [INIT_DEVICE_OPTIONS](state, selectedDeviceOptions) {
      state.isLoading = true;
      state.error = null;
      state.selectedTrainingPlanDeviceOptions = selectedDeviceOptions;
    },
    [UPDATE_TRAINING_PLAN_DEVICE_OPTION](state, data) {
      state.isLoading = true;
      state.error = null;
      if (undefined === state.selectedTrainingPlanDeviceOptions[data.id]) {
        state.selectedTrainingPlanDeviceOptions[data.id] = {};
      }
      state.selectedTrainingPlanDeviceOptions[data.id].id = data.id;
      state.selectedTrainingPlanDeviceOptions[data.id].name = data.name;
      state.selectedTrainingPlanDeviceOptions[data.id].trainingPlanXDeviceOptionId = data.trainingPlanXDeviceOptionId;
      state.selectedTrainingPlanDeviceOptions[data.id].value = data.value;
      state.selectedTrainingPlanDeviceOptions[data.id].defaultValue = data.defaultValue;
      state.selectedTrainingPlanDeviceOptions[data.id].isDefault = data.isDefault;
      state.exerciseOptionKey = Math.random();
    },
    [FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS](state) {
      state.isLoading = true;
      state.error = null;
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_TRAINING_PLAN_EXERCISE_OPTION);
      try {
        let response = await TrainingPlanExerciseOptionsController.create(data.optionValue, data.exerciseOptionId, data.trainingPlanExercise);
        commit(CREATING_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_TRAINING_PLAN_EXERCISE_OPTION_FAILURE, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_TRAINING_PLAN_EXERCISE_OPTION);
      try {
        let response = await TrainingPlanExerciseOptionsController.update(data.trainingPlanXExerciseOption, data.optionValue, data.exerciseOption, data.trainingPlanExercise);
        commit(UPDATE_TRAINING_PLAN_EXERCISE_OPTION_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_TRAINING_PLAN_EXERCISE_OPTION_FAILURE, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_TRAINING_PLAN_EXERCISE_OPTION);
    },
    initSelectedTrainingPlanExerciseOptions({ commit }, selectedExerciseOptions) {
      commit(INIT_EXERCISE_OPTIONS, selectedExerciseOptions);
    },
    initDeviceOptions({ commit }, selectedDeviceOptions) {
      commit(INIT_DEVICE_OPTIONS, selectedDeviceOptions);
    },
    findAll({ commit }) {
      commit(FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS);
      return this.state.selectedTrainingPlanExerciseOptions;
    },
    exerciseOptionKey({ commit }) {
      commit(FETCHING_TRAINING_PLAN_EXERCISE_OPTIONS_KEY);
      return this.state.exerciseOptionKey;
    },
    deviceOptionKey({ commit }) {
      commit(FETCHING_TRAINING_PLAN_DEVICE_OPTIONS_KEY);
      return this.state.deviceOptionKey;
    },
    updateSelectedTrainingPlanExerciseOption({ commit }, data) {
      commit(UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_OPTION, data);
    },
    updateSelectedTrainingPlanExerciseDeviceOption({ commit }, data) {
      commit(UPDATE_SELECTED_TRAINING_PLAN_EXERCISE_DEVICE_OPTION, data);
    }
  }
};
