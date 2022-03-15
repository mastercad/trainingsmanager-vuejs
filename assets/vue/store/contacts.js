import ContactController from "../controllers/contacts";

const CREATING_CONTACT = "CREATING_CONTACT",
  CREATING_CONTACT_SUCCESS = "CREATING_CONTACT_SUCCESS",
  CREATING_CONTACT_ERROR = "CREATING_CONTACT_ERROR",
  UPDATE_CONTACT = "UPDATE_CONTACT",
  UPDATE_CONTACT_SUCCESS = "UPDATE_CONTACT_SUCCESS",
  UPDATE_CONTACT_ERROR = "UPDATE_CONTACT_ERROR",
  DELETE_CONTACT = "DELETE_CONTACT",
  DELETE_CONTACT_SUCCESS = "DELETE_CONTACT_SUCCESS",
  DELETE_CONTACT_ERROR = "DELETE_CONTACT_ERROR",
  FETCHING_CONTACTS = "FETCHING_CONTACTS",
  FETCHING_CONTACTS_SUCCESS = "FETCHING_CONTACTS_SUCCESS",
  FETCHING_CONTACTS_ERROR = "FETCHING_CONTACTS_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    contacts: []
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
    hasContacts(state) {
      return state.contacts.length > 0;
    },
    contacts(state) {
      return state.contacts;
    }
  },
  mutations: {
    [CREATING_CONTACT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [CREATING_CONTACT_SUCCESS](state, contact) {
      state.isLoading = false;
      state.error = null;
      //      state.contacts.unshift(contact);
      state.contacts.push(contact);
    },
    [CREATING_CONTACT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.contacts = [];
    },
    [UPDATE_CONTACT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [UPDATE_CONTACT_SUCCESS](state, contact) {
      state.isLoading = false;
      state.error = null;
      for (let index = 0; index < state.contacts.length; ++index) {
        if (state.contacts[index].id === contact.id) {
          state.contacts[index] = contact;
        }
      }
    },
    [UPDATE_CONTACT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.contacts = [];
    },
    [DELETE_CONTACT](state) {
      state.isLoading = true;
      state.error = null;
    },
    [DELETE_CONTACT_SUCCESS](state, id) {
      state.isLoading = false;
      state.error = null;

      let index = state.contacts.findIndex(currentContact => currentContact.id == id);
      state.contacts.splice(index, 1);
    },
    [DELETE_CONTACT_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.contacts = [];
    },
    [FETCHING_CONTACTS](state) {
      state.isLoading = true;
      state.error = null;
      state.contacts = [];
    },
    [FETCHING_CONTACTS_SUCCESS](state, contacts) {
      state.isLoading = false;
      state.error = null;
      state.contacts = contacts;
    },
    [FETCHING_CONTACTS_ERROR](state, error) {
      state.isLoading = false;
      state.error = error;
      state.contacts = [];
    }
  },
  actions: {
    async create({ commit }, data) {
      commit(CREATING_CONTACT);
      try {
        let response = await ContactController.create(data.firstName, data.lastName, data.emailAddress);
        commit(CREATING_CONTACT_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(CREATING_CONTACT_ERROR, error);
        return null;
      }
    },
    async update({ commit }, data) {
      commit(UPDATE_CONTACT);
      try {
        let response = await ContactController.update(data.id, data.firstName, data.lastName, data.emailAddress);
        commit(UPDATE_CONTACT_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(UPDATE_CONTACT_ERROR, error);
        return null;
      }
    },
    async delete( { commit }, id) {
      commit(DELETE_CONTACT);
      try {
        let response = await ContactController.delete(id);
        commit(DELETE_CONTACT_SUCCESS, id);
        return response.data;
      } catch (error) {
        commit(DELETE_CONTACT_ERROR, error);
        return null;
      }
    },
    async findAll({ commit }) {
      commit(FETCHING_CONTACTS);
      try {
        let response = await ContactController.findAll();
        commit(FETCHING_CONTACTS_SUCCESS, response.data);
        return response.data;
      } catch (error) {
        commit(FETCHING_CONTACTS_ERROR, error);
        return null;
      }
    }
  }
};
