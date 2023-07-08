const CREATING_TRAINING_PLAN_EXERCISE = "CREATING_TRAINING_PLAN_EXERCISE",
  UPDATE_TRAINING_PLAN_EXERCISE = "UPDATE_TRAINING_PLAN_EXERCISE",
  DELETE_TRAINING_PLAN_EXERCISE = "DELETE_TRAINING_PLAN_EXERCISE",
  FETCHING_TRAINING_PLAN_EXERCISE = "FETCHING_TRAINING_PLAN_EXERCISE";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    exerciseKey: 'UNDEFINED',
    deviceKey: 'UNDEFINED',
    selectedTrainingPlanExercise: {},
    selectedTrainingPlanDevice: {}
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
      return state.selectedTrainingPlanExercise.length > 0;
    },
    selectedTrainingPlanExercise(state) {
      return state.selectedTrainingPlanExercise;
    },
    selectedTrainingPlanDevice(state) {
      return state.selectedTrainingPlanDevice;
    },
    exerciseKey(state) {
      return state.exerciseKey;
    },
    deviceKey(state) {
      return state.deviceKey;
    }
  },
  mutations: {
    [CREATING_TRAINING_PLAN_EXERCISE](state) {
      state.isLoading = true;
      state.error = null;
      state.selectedTrainingPlanExercise = {};
    },
    [UPDATE_TRAINING_PLAN_EXERCISE](state, data) {
      state.isLoading = true;
      state.error = null;
      if (undefined === state.selectedTrainingPlanExercise[data.id]) {
        state.selectedTrainingPlanExercise[data.id] = {};
      }
      state.selectedTrainingPlanExercise[data.id].id = data.id;
      state.selectedTrainingPlanExercise[data.id].name = data.name;
      state.selectedTrainingPlanExercise[data.id].trainingPlanXExerciseId = data.trainingPlanXExerciseId;
      state.selectedTrainingPlanExercise[data.id].value = data.value;
      state.selectedTrainingPlanExercise[data.id].defaultValue = data.defaultValue;
      state.selectedTrainingPlanExercise[data.id].isDefault = data.isDefault;
    },
    [DELETE_TRAINING_PLAN_EXERCISE](state, id) {
      state.isLoading = true;
      state.error = null;
    },
    [FETCHING_TRAINING_PLAN_EXERCISE](state) {
      state.isLoading = true;
      state.error = null;
    }
  },
  actions: {
    createExercise({ commit }) {
      commit(CREATING_TRAINING_PLAN_EXERCISE);
      this.state.selectedTrainingPlanExercise = {};
    },
    deleteExercise( { commit }, id) {
      commit(DELETE_TRAINING_PLAN_EXERCISE, id);
    },
    initExercise({ commit }, selectedExercise) {
      commit(INIT_EXERCISE, selectedExercise);
    },
    initDevice({ commit }, selectedDevice) {
      commit(INIT_DEVICE, selectedDevice);
    },
    findAll({ commit }) {
      commit(FETCHING_TRAINING_PLAN_EXERCISE);
      return this.state.selectedTrainingPlanExercise;
    },
    exerciseKey({ commit }) {
      commit(FETCHING_TRAINING_PLAN_EXERCISE_KEY);
      return this.state.exerciseKey;
    },
    deviceKey({ commit }) {
      commit(FETCHING_TRAINING_PLAN_DEVICE_KEY);
      return this.state.deviceKey;
    },
    updateExercise({ commit }, data) {
      commit(UPDATE_TRAINING_PLAN_EXERCISE, data);
    },
    updateDevice({ commit }, data) {
      commit(UPDATE_TRAINING_PLAN_DEVICE, data);
    }
  }
};
