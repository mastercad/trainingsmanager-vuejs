import Vue from "vue";
import Vuex from "vuex";
import ContactModule from "./contacts";
import ExerciseModule from "./exercises";
import DevicesModule from "./devices";
import ExerciseOptionModule from "./exerciseOptions";
import DevicesOptionModule from "./deviceOptions";
import SecurityModule from "./security";
import TrainingPlansModule from "./trainingPlans";
import TrainingPlanLayoutsModule from "./trainingPlanLayouts";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    security: SecurityModule,
    contacts: ContactModule,
    exercises: ExerciseModule,
    devices: DevicesModule,
    exerciseOptions: ExerciseOptionModule,
    deviceOptions: DevicesOptionModule,
    trainingPlans: TrainingPlansModule,
    trainingPlanLayouts: TrainingPlanLayoutsModule
  }
});
