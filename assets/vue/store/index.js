import Vue from "vue";
import Vuex from "vuex";
import ContactModule from "./contacts";
import ExerciseModule from "./exercises";
import DevicesModule from "./devices";
import ExerciseOptionModule from "./exerciseOptions";
import ExerciseTypeModule from "./exerciseTypes";
import DevicesOptionModule from "./deviceOptions";
import DeviceGroups from "./deviceGroups";
import SecurityModule from "./security";
import TrainingPlansModule from "./trainingPlans";
import TrainingPlanLayoutsModule from "./trainingPlanLayouts";
import TrainingPlanExerciseOptionsModule from "./trainingPlanExerciseOptions";
import MusclesModule from "./muscles";
import MuscleGroupsModule from "./muscleGroups";

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
