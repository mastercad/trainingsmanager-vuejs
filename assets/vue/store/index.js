import Vue from "vue";
import Vuex from "vuex";
import ContactModule from "./contacts";
import ExerciseModule from "./exercises";
import SecurityModule from "./security";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    security: SecurityModule,
    contacts: ContactModule,
    exercises: ExerciseModule
  }
});
