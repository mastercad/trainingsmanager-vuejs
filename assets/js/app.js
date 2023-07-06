/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import axios from "axios";
import Vue from 'vue';

Vue.config.devtools = true;

import App from './components/App.vue';
import router from './router/index.js';
import store from "./store/index.js";
import './../styles/app.css';
import { BootstrapVue, IconsPlugin, DropdownPlugin } from 'bootstrap-vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { faJs, faVuejs } from '@fortawesome/free-brands-svg-icons';
import VueSimpleContextMenu from "vue-simple-context-menu";
import VueFormWizard  from 'vue-form-wizard';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';

library.add(faPlus, faJs, faVuejs);

Vue.component('VueSimpleContextMenu', VueSimpleContextMenu);
Vue.component('FontAwesomeIcon', FontAwesomeIcon);

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(DropdownPlugin);
Vue.use(VueFormWizard);

axios.defaults.withCredentials = true;

axios.interceptors.response.use(
  function(response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
  },
  async function(error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    const originalRequest = error.config;
    if (
      401 === error.response.status
      && originalRequest.url.includes("api/token/refresh")
    ) {
      store.dispatch("security/clearUserData");
      router.push("/login");
      return Promise.reject(error);
    } else if (401 === error.response.status
      && !originalRequest._retry
      && store.getters['security/isAuthenticated']
    ) {
      originalRequest._retry = true;
      await store.dispatch("security/refresh", store.getters['security/refreshToken']);

      if (store.getters['security/isAuthenticated']) {
        return axios(originalRequest);
      }
    }
    return Promise.reject(error);
  }
);

axios.interceptors.request.use(
  function(config) {
    // Do something before request is sent
    // use getters to retrieve the access token from vuex
    // store
    const token = store.getters['security/accessToken'];
    const refreshToken = store.getters['security/refreshToken'];
    if (token) {
      config.headers.Authorization = "Bearer "+token;
      config.headers['X-Refresh-Token'] = refreshToken;
    }
    return config;
  },
  function(error) {
    return Promise.reject(error);
  }
);

const app = new Vue({
  components: { App },
  render: h => h(App),
  template: '<App/>',
  router,
  store
});

app.$mount('#app');
