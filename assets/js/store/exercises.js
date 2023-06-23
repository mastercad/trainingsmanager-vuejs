import ExerciseController from "../controllers/exercises.js";

const CREATING_EXERCISE = "CREATING_EXERCISE",
  CREATING_EXERCISE_SUCCESS = "CREATING_EXERCISE_SUCCESS",
  CREATING_EXERCISE_ERROR = "CREATING_EXERCISE_ERROR",
  UPDATE_EXERCISE = "UPDATE_EXERCISE",
  UPDATE_EXERCISE_SUCCESS = "UPDATE_EXERCISE_SUCCESS",
  UPDATE_EXERCISE_ERROR = "UPDATE_EXERCISE_ERROR",
  DELETE_EXERCISE = "DELETE_EXERCISE",
  DELETE_EXERCISE_SUCCESS = "DELETE_EXERCISE_SUCCESS",
  DELETE_EXERCISE_ERROR = "DELETE_EXERCISE_ERROR",
  DELETE_EXERCISE_IMAGE = "DELETE_EXERCISE_IMAGE",
  DELETE_EXERCISE_IMAGE_SUCCESS = "DELETE_EXERCISE_IMAGE_SUCCESS",
  DELETE_EXERCISE_IMAGE_ERROR = "DELETE_EXERCISE_IMAGE_ERROR",
  DELETE_UPLOAD_IMAGE = "DELETE_UPLOAD_IMAGE",
  DELETE_UPLOAD_IMAGE_SUCCESS = "DELETE_UPLOAD_IMAGE_SUCCESS",
  DELETE_UPLOAD_IMAGE_ERROR = "DELETE_UPLOAD_IMAGE_ERROR",
  FETCHING_EXERCISES = "FETCHING_EXERCISES",
  FETCHING_EXERCISES_SUCCESS = "FETCHING_EXERCISES_SUCCESS",
  FETCHING_EXERCISES_ERROR = "FETCHING_EXERCISES_ERROR",
  FETCHING_EXERCISE = "FETCHING_EXERCISE",
  FETCHING_EXERCISE_SUCCESS = "FETCHING_EXERCISE_SUCCESS",
  FETCHING_EXERCISE_ERROR = "FETCHING_EXERCISE_ERROR",
  FETCHING_EXERCISE_FOR_EDIT = "FETCHING_EXERCISE_FOR_EDIT",
  FETCHING_EXERCISE_FOR_EDIT_SUCCESS = "FETCHING_EXERCISE_FOR_EDIT_SUCCESS",
  FETCHING_EXERCISE_FOR_EDIT_ERROR = "FETCHING_EXERCISE_FOR_EDIT_ERROR",
  FETCHING_EXERCISE_IMAGES = "FETCHING_EXERCISE_IMAGES",
  FETCHING_EXERCISE_IMAGES_SUCCESS = "FETCHING_EXERCISE_IMAGES_SUCCESS",
  FETCHING_EXERCISE_IMAGES_ERROR = "FETCHING_EXERCISE_IMAGES_ERROR",
  REGISTER_EXERCISE = "REGISTER_EXERCISE",
  UNREGISTER_EXERCISE = "UNREGISTER_EXERCISE";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isImagesLoading: false,
    isPanelLoading: false,
    error: null,
    exercises: [],
    exercise: null,
    exerciseImages: []
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    isPanelLoading(state) {
      return state.isPanelLoading;
    },
    isImagesLoading(state) {
      return state.isImagesLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    hasExercises(state) {
      return state.exercises.length > 0;
    },
    exercises(state) {
      return state.exercises;
    },
    exercise(state) {
      return state.exercise;
    },
    exerciseImages(state) {
      return state.exerciseImages;
    },
    findOldRegistered(state) {
      let index = state.exercises.findIndex(currentExercise => (typeof currentExercise.id === 'string' || currentExercise.id instanceof String) && currentExercise.id.startsWith('exercise_'));

      return state.exercises[index];
    }
  },
  mutations: {
    [CREATING_EXERCISE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_EXERCISE_SUCCESS](state, data) {
      let exercise = data.exercise;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.exercises.findIndex(currentExercise => currentExercise.id == previousId);

      if (0 <= index) {
        state.exercises[index].id = exercise.id;
        state.exercises[index].creator = exercise.creator;
        state.exercises[index].created = exercise.created;
        state.exercises[index].updated = exercise.updated;
        state.exercises[index].updater = exercise.updater;
        state.exercises[index].name = exercise.name;
        state.exercises[index].previewPicturePath = exercise.previewPicturePath;
        state.exercises[index].seoLink = exercise.seoLink;
        state.exercises[index].exerciseXDeviceOptions = exercise.exerciseXDeviceOptions;
        state.exercises[index].exerciseXExerciseOptions = exercise.exerciseXExerciseOptions;
        state.exercises[index].exerciseXExerciseType = exercise.exerciseXExerciseType;
        state.exercises[index].exerciseXDevices = exercise.exerciseXDevices;
      }
    },
    [CREATING_EXERCISE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exercises = [];
    },
    [UPDATE_EXERCISE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [UPDATE_EXERCISE_SUCCESS](state, data) {
      let exercise = data.exercise;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.exercises.findIndex(currentExercise => currentExercise.id == previousId);

      if (0 <= index) {
        state.exercises[index].id = exercise.id;
        state.exercises[index].creator = exercise.creator;
        state.exercises[index].created = exercise.created;
        state.exercises[index].updated = exercise.updated;
        state.exercises[index].updater = exercise.updater;
        state.exercises[index].name = exercise.name;
        state.exercises[index].previewPicturePath = exercise.previewPicturePath;
        state.exercises[index].seoLink = exercise.seoLink;
        state.exercises[index].exerciseXDeviceOptions = exercise.exerciseXDeviceOptions;
        state.exercises[index].exerciseXExerciseOptions = exercise.exerciseXExerciseOptions;
        state.exercises[index].exerciseXExerciseType = exercise.exerciseXExerciseType;
        state.exercises[index].exerciseXDevices = exercise.exerciseXDevices;
      }
    },
    [UPDATE_EXERCISE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exercises = [];
    },
    [DELETE_EXERCISE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [DELETE_EXERCISE_SUCCESS](state, id) {
      state.isPanelLoading = false;
      state.error = null;

      let index = state.exercises.findIndex(currentExercise => currentExercise.id == id);
      state.exercises.splice(index, 1);
    },
    [DELETE_EXERCISE_IMAGE](state) {
      state.isImagesLoading = true;
      state.error = null;
    },
    [DELETE_EXERCISE_IMAGE_SUCCESS](state) {
      state.isImagesLoading = false;
      state.error = null;
    },
    [DELETE_EXERCISE_IMAGE_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
    },
    [DELETE_UPLOAD_IMAGE](state) {
      state.isImagesLoading = true;
      state.error = null;
    },
    [DELETE_UPLOAD_IMAGE_SUCCESS](state) {
      state.isImagesLoading = false;
      state.error = null;
    },
    [DELETE_UPLOAD_IMAGE_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
    },
    [FETCHING_EXERCISES](state) {
      state.isLoading = true;
      state.error = null;
      state.exercises = [];
    },
    [FETCHING_EXERCISES_SUCCESS](state, exercises) {
      state.isLoading = false;
      state.error = null;
      state.exercises = exercises;
    },
    [FETCHING_EXERCISES_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.exercises = [];
    },
    [FETCHING_EXERCISE](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.exercise = null;
    },
    [FETCHING_EXERCISE_SUCCESS](state, exercise) {
      state.isPanelLoading = false;
      state.error = null;
      state.exercise = exercise;
    },
    [FETCHING_EXERCISE_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exercises = [];
    },
    [FETCHING_EXERCISE_FOR_EDIT](state) {
      state.isPanelLoading = true;
      state.error = null;
      state.exercise = null;
    },
    [FETCHING_EXERCISE_FOR_EDIT_SUCCESS](state, exercise) {
      state.isPanelLoading = false;
      state.error = null;
      state.exercise = exercise;
    },
    [FETCHING_EXERCISE_FOR_EDIT_ERROR](state, error) {
      state.isPanelLoading = false;
      state.error = error;
      state.exercise = null;
    },
    [FETCHING_EXERCISE_IMAGES](state) {
      state.isImagesLoading = true;
      state.error = null;
      state.exerciseImages = null;
    },
    [FETCHING_EXERCISE_IMAGES_SUCCESS](state, images) {
      state.isImagesLoading = false;
      state.error = null;
      state.exerciseImages = images;
    },
    [FETCHING_EXERCISE_IMAGES_ERROR](state, error) {
      state.isImagesLoading = false;
      state.error = error;
      state.exerciseImages = null;
    },
    [REGISTER_EXERCISE](state, exercise) {
      state.exercises.push(exercise);

      return exercise;
    },
    [UNREGISTER_EXERCISE](state, exercise) {
      let index = state.exercises.findIndex(currentExercise => (currentExercise.id === exercise.id));

      return state.exercises.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, exercise) {
      commit(CREATING_EXERCISE);
      try {
        const origId = exercise.id; // expect the current id is autogenerated by frontend and leads in problems during save process. but need to persist to replace current entry with response
        exercise.id = null;
        let response = await ExerciseController.create(exercise);
        commit(CREATING_EXERCISE_SUCCESS, {exercise: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_EXERCISE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, exercise) {
      commit(UPDATE_EXERCISE);
      try {
        let response = await ExerciseController.update(exercise);
        commit(UPDATE_EXERCISE_SUCCESS, {exercise: response.data, origId: exercise.id});
        return response.data;
      } catch (error) {
        commit(UPDATE_EXERCISE_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_EXERCISE);
      try {
        let response = await ExerciseController.delete(id);
        commit(DELETE_EXERCISE_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_EXERCISE_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_EXERCISES);
      try {
        let response = await ExerciseController.findAll();
        commit(FETCHING_EXERCISES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISES_ERROR, error);
        return null;
      }
    },
    async findExercise({ commit }, id) {
      commit(FETCHING_EXERCISE);
      try {
        let response = await ExerciseController.findExercise(id);
        commit(FETCHING_EXERCISE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_ERROR, error);
        return null;
      }
    },
    async findExerciseForEdit({ commit }, id) {
      commit(FETCHING_EXERCISE_FOR_EDIT);
      try {
        let response = await ExerciseController.findExerciseForEdit(id);
        commit(FETCHING_EXERCISE_FOR_EDIT_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_FOR_EDIT_ERROR, error);
        return null;
      }
    },
    async loadImages({ commit }, id) {
      commit(FETCHING_EXERCISE_IMAGES);
      try {
        let response = await ExerciseController.loadImages(id);
        commit(FETCHING_EXERCISE_IMAGES_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_EXERCISE_IMAGES_ERROR, error);
        return null;
      }
    },
    async deleteUploadImage({ commit }, image) {
      commit(DELETE_UPLOAD_IMAGE);
      try {
        let response = await ExerciseController.deleteUploadImage(image);
        commit(DELETE_UPLOAD_IMAGE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(DELETE_UPLOAD_IMAGE_ERROR, error);
        return null;
      }
    },
    async deleteExerciseImage({ commit }, payload) {
      commit(DELETE_EXERCISE_IMAGE);
      try {
        let response = await ExerciseController.deleteExerciseImage(payload.fileName, payload.id);
        commit(DELETE_EXERCISE_IMAGE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(DELETE_EXERCISE_IMAGE_ERROR, error);
        return null;
      }
    },
    async register({ commit }, exercise) {
      return commit(REGISTER_EXERCISE, exercise);
    },
    async unregister({ commit }, exercise) {
      return commit(UNREGISTER_EXERCISE, exercise);
    }
  }
};
