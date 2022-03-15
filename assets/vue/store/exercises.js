import ExerciseController from "../controllers/exercises";

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
  FETCHING_EXERCISE_IMAGES_ERROR = "FETCHING_EXERCISE_IMAGES_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    isImageLoading: false,
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
    }
  },
  mutations: {
    [CREATING_EXERCISE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_EXERCISE_SUCCESS](state, exercise) {
      state.isPanelLoading = false;
      state.error = null;
      //      state.exercises.unshift(exercise);
      state.exercises.push(exercise);
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
    [UPDATE_EXERCISE_SUCCESS](state, exercise) {
      state.isPanelLoading = false;
      state.error = null;
      for (let index = 0; index < state.exercises.length; ++index) {
        if (state.exercises[index].id === exercise.id) {
          state.exercises[index] = exercise;
        }
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
      state.isImageLoading = true;
      state.error = null;
    },
    [DELETE_EXERCISE_IMAGE_SUCCESS](state) {
      state.isImageLoading = false;
      state.error = null;
    },
    [DELETE_EXERCISE_IMAGE_ERROR](state, error) {
      state.isImageLoading = false;
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
      state.isImageLoading = true;
      state.error = null;
      state.exerciseImages = null;
    },
    [FETCHING_EXERCISE_IMAGES_SUCCESS](state, images) {
      state.isImageLoading = false;
      state.error = null;
      state.exerciseImages = images;
      console.log(state.exerciseImages);
    },
    [FETCHING_EXERCISE_IMAGES_ERROR](state, error) {
      state.isImageLoading = false;
      state.error = error;
      state.exerciseImages = null;
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_EXERCISE);
      try {
        let response = await ExerciseController.create(data.name, data.seoLink, data.description, data.specialFeatures, data.previewPicturePath);
        commit(CREATING_EXERCISE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_EXERCISE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_EXERCISE);
      try {
        let response = await ExerciseController.update(data.id, data.name, data.seoLink, data.description, data.specialFeatures, data.previewPicturePath);
        commit(UPDATE_EXERCISE_SUCCESS, response.data);
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
    async deleteImage({ commit }, image) {
      commit(DELETE_EXERCISE_IMAGE);
      try {
        let response = await ExerciseController.deleteImage(image);
        commit(DELETE_EXERCISE_IMAGE_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(DELETE_EXERCISE_IMAGE_ERROR, error);
        return null;
      }
    }
  }
};
