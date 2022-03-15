/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import axios from "axios";
import Vue from 'vue';
import App from './components/App';
import router from './router';
import store from "./store";
import './../styles/app.css';
import VueJsonLD from 'vue-jsonld';
// Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip
// import { Button, Dropdown, Modal, Tooltip } from "bootstrap";
import { BootstrapVue, IconsPlugin, DropdownPlugin } from 'bootstrap-vue'
import VueSimpleContextMenu from "vue-simple-context-menu";

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-simple-context-menu/dist/vue-simple-context-menu.css';

Vue.component('vue-simple-context-menu', VueSimpleContextMenu);

Vue.use(VueJsonLD);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(DropdownPlugin);

//Vue.use(Button);
//Vue.use(Dropdown);
//Vue.use(Modal);
//Vue.use(Tooltip);

axios.defaults.withCredentials = true;

const app = new Vue({
  components: { App },
  render: h => h(App),
  template: '<App/>',
  router,
  store
});

app.$mount('#app');
