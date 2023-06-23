import MuscleController from "../controllers/muscles.js";

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
  FETCHING_MUSCLE_ERROR = "FETCHING_MUSCLE_ERROR",
  REGISTER_MUSCLE = "REGISTER_MUSCLE",
  UNREGISTER_MUSCLE = "UNREGISTER_MUSCLE";

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
    },
    findOldRegistered(state) {
      let index = state.muscles.findIndex(currentMuscle => (typeof currentMuscle.id === 'string' || currentMuscle.id instanceof String) && currentMuscle.id.startsWith('muscle_'));

      return state.muscles[index];
    }
  },
  mutations: {
    [CREATING_MUSCLE](state) {
      state.isPanelLoading = true;
      state.error = null;
    },
    [CREATING_MUSCLE_SUCCESS](state, data) {
      let muscle = data.muscle;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.muscles.findIndex(currentMuscle => currentMuscle.id == previousId);

      if (0 <= index) {
        state.muscles[index].id = muscle.id;
        state.muscles[index].name = muscle.name;
        state.muscles[index].seoLink = muscle.seoLink;
        state.muscles[index].creator = muscle.creator;
        state.muscles[index].created = muscle.created;
        state.muscles[index].updated = muscle.updated;
        state.muscles[index].updater = muscle.updater;
        state.muscles[index].muscleXMuscleGroups = muscle.muscleXMuscleGroups;
      }
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
    [UPDATE_MUSCLE_SUCCESS](state, data) {
      let muscle = data.muscle;
      let previousId = data.origId;

      state.isPanelLoading = false;
      state.error = null;

      let index = state.muscles.findIndex(currentMuscle => currentMuscle.id == previousId);

      if (0 <= index) {
        state.muscles[index].id = muscle.id;
        state.muscles[index].name = muscle.name;
        state.muscles[index].seoLink = muscle.seoLink;
        state.muscles[index].creator = muscle.creator;
        state.muscles[index].created = muscle.created;
        state.muscles[index].updated = muscle.updated;
        state.muscles[index].updater = muscle.updater;
        state.muscles[index].muscleXMuscleGroups = muscle.muscleXMuscleGroups;
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
    },
    [REGISTER_MUSCLE](state, muscle) {
      state.muscles.push(muscle);

      return muscle;
    },
    [UNREGISTER_MUSCLE](state, muscle) {
      let index = state.muscles.findIndex(currentMuscle => (currentMuscle.id === muscle.id));

      return state.muscles.splice(index, 1);
    }
  },
  actions: {
    async create({ commit }, muscle) {
      commit(CREATING_MUSCLE);
      try {
        const origId = muscle.id; // expect the current id is autogenerated by frontend and leads in problems during save process. but need to persist to replace current entry with response
        muscle.id = null;
        let response = await MuscleController.create(muscle);
        commit(CREATING_MUSCLE_SUCCESS, {muscle: response.data, origId: origId});
        return response.data;
      } catch (error) {
        commit(CREATING_MUSCLE_ERROR, error);
        return null;
      }
    },
    async update({ commit }, muscle) {
      commit(UPDATE_MUSCLE);
      try {
        let response = await MuscleController.update(muscle);
        commit(UPDATE_MUSCLE_SUCCESS, {muscle: response.data, origId: muscle.id});
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
    },
    async register({ commit }, muscle) {
      return commit(REGISTER_MUSCLE, muscle);
    },
    async unregister({ commit }, muscle) {
      return commit(UNREGISTER_MUSCLE, muscle);
    }
  }
};
