import SecurityAPI from "../controllers/security.js";

const AUTHENTICATING = "AUTHENTICATING",
  AUTHENTICATING_SUCCESS = "AUTHENTICATING_SUCCESS",
  AUTHENTICATING_ERROR = "AUTHENTICATING_ERROR",
  REGISTRATION = "REGISTRATION",
  REGISTRATION_SUCCESS = "REGISTRATION_SUCCESS",
  REGISTRATION_ERROR = "REGISTRATION_ERROR",
  PROVIDING_REFRESH_SUCCESS = "PROVIDING_REFRESH_SUCCESS",
  PROVIDING_REFRESH_ERROR = "PROVIDING_REFRESH_ERROR",
  PROVIDING_RELOAD_SUCCESS = "PROVIDING_RELOAD_SUCCESS",
  LOGOUT = "LOGOUT",
  LOGOUT_SUCCESS = "LOGOUT_SUCCESS",
  LOGOUT_FAILED = "LOGOUT_FAILED";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    isAuthenticated: false,
    user: null,
    token: null,
    refreshToken: null
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
    isAuthenticated(state) {
      return JSON.parse(state.isAuthenticated);
    },
    hasRole(state) {
      return role => {
        return state.user.roles.indexOf(role) !== -1;
      };
    },
    accessToken(state) {
      return state.token;
    },
    refreshToken(state) {
      return state.refreshToken;
    }
  },
  mutations: {
    [AUTHENTICATING](state) {
      state.isLoading = true;
      state.error = null;
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
    },
    [AUTHENTICATING_SUCCESS](state, payload) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
      state.user = payload.user;
      state.token = payload.token;
      state.refreshToken = payload.refresh_token;
      localStorage.setItem('isAuthenticated', true);
      localStorage.setItem('user', payload.user);
      localStorage.setItem('token', payload.token);
      localStorage.setItem('refreshToken', payload.refresh_token);
    },
    [AUTHENTICATING_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
    },
    [REGISTRATION](state) {
      state.isLoading = true;
      state.error = null;
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
    },
    [REGISTRATION_SUCCESS](state, payload) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
      state.user = payload.user;
      state.token = payload.token;
      state.refreshToken = payload.refresh_token;
      localStorage.setItem('isAuthenticated', true);
      localStorage.setItem('user', payload.user);
      localStorage.setItem('token', payload.token);
      localStorage.setItem('refreshToken', payload.refresh_token);
    },
    [REGISTRATION_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
    },
    [PROVIDING_REFRESH_SUCCESS](state, payload) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
      state.user = payload.user;
      state.token = payload.token;
      state.refreshToken = payload.refresh_token;
      localStorage.setItem('isAuthenticated', true);
      localStorage.setItem('user', payload.user);
      localStorage.setItem('token', payload.token);
      localStorage.setItem('refreshToken', payload.refresh_token);
    },
    [PROVIDING_RELOAD_SUCCESS](state, payload) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = payload.isAuthenticated;
      state.user = payload.user;
      state.token = payload.token;
      state.refreshToken = payload.refreshToken;
      localStorage.setItem('isAuthenticated', payload.isAuthenticated);
      localStorage.setItem('user', payload.user);
      localStorage.setItem('token', payload.token);
      localStorage.setItem('refreshToken', payload.refreshToken);
    },
    [PROVIDING_REFRESH_ERROR](state, error) {
      state.error = error;
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
    },
    [LOGOUT_SUCCESS](state) {
      state.isAuthenticated = false;
      state.refreshToken = null;
      state.user = null;
      state.token = null;
      localStorage.removeItem('isAuthenticated');
      localStorage.removeItem('user');
      localStorage.removeItem('token');
      localStorage.removeItem('refreshToken');
    },
    [LOGOUT_FAILED](state, error) {
      state.error = error;
    },
    ['clearUserData'](state) {
      state.isAuthenticated = false;
      state.user = null;
      state.token = null;
      state.refreshToken = null;
      localStorage.removeItem('isAuthenticated');
      localStorage.removeItem('token');
      localStorage.removeItem('refreshToken');
      localStorage.removeItem('user');
    }
  },
  actions: {
    async register({commit}, payload) {
      commit(REGISTRATION);
      try {
        let response = await SecurityAPI.register(payload.email, payload.login, payload.firstName, payload.lastName, payload.firstPassword, payload.secondPassword);
        commit(REGISTRATION_SUCCESS, response.data);
        return response;
      } catch (error) {
        commit(REGISTRATION_ERROR, error);
        return null;
      }
    },
    async login({commit}, payload) {
      commit(AUTHENTICATING);
      try {
        let response = await SecurityAPI.login(payload.login, payload.password);
        commit(AUTHENTICATING_SUCCESS, response.data);
        return response;
      } catch (error) {
        commit(AUTHENTICATING_ERROR, error);
        return null;
      }
    },
    async refresh({commit}, refreshToken) {
      try {
        let response = await SecurityAPI.refresh(refreshToken);
        commit(PROVIDING_REFRESH_SUCCESS, response.data);
        return response;
      } catch (error) {
        if (401 !== error.response.data.code) {
          commit(PROVIDING_REFRESH_ERROR, error);
        }
        return null;
      }
    },
    async logout({commit}) {
      commit(LOGOUT);
      try {
        let response = await SecurityAPI.logout();
        commit (LOGOUT_SUCCESS, response.data);
        return response;
      } catch (error) {
        commit (LOGOUT_FAILED, error);
        return null;
      }
    },
    onReload({commit}, payload) {
      commit(PROVIDING_RELOAD_SUCCESS, payload);
    },
    clearUserData({commit}) {
      commit('clearUserData');
    }
  }
};
