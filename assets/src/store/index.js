import Vue from "vue";
import Vuex from "vuex";
import ContactModule from "./contacts.js";
import ExerciseModule from "./exercises.js";
import DevicesModule from "./devices.js";
import ExerciseOptionModule from "./exerciseOptions.js";
import ExerciseTypeModule from "./exerciseTypes.js";
import DevicesOptionModule from "./deviceOptions.js";
import DeviceGroups from "./deviceGroups.js";
import SecurityModule from "./security.js";
import TrainingPlansModule from "./trainingPlans.js";
import TrainingPlanLayoutsModule from "./trainingPlanLayouts.js";
import TrainingPlanExerciseOptionsModule from "./trainingPlanExerciseOptions.js";
import MusclesModule from "./muscles.js";
import MuscleGroupsModule from "./muscleGroups.js";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    security: SecurityModule,
    contacts: ContactModule,
    exercises: ExerciseModule,
    devices: DevicesModule,
    exerciseOptions: ExerciseOptionModule,
    exerciseTypes: ExerciseTypeModule,
    deviceOptions: DevicesOptionModule,
    deviceGroups: DeviceGroups,
    trainingPlans: TrainingPlansModule,
    trainingPlanLayouts: TrainingPlanLayoutsModule,
    trainingPlanExerciseOptions: TrainingPlanExerciseOptionsModule,
    muscles: MusclesModule,
    muscleGroups: MuscleGroupsModule
  }
});
