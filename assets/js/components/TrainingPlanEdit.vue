<template>
  <b-container fluid>
    <h1 class="row">
      TrainingPlan Layout Normal Edit
    </h1>

    <div>
      <b-tabs content-class="mt-3">
        <b-tab
          title="First"
          active
        >
          <p>I'm the first tab</p>
        </b-tab>
        <b-tab title="Second">
          <p>I'm the second tab</p>
        </b-tab>
        <b-tab
          title="Disabled"
          disabled
        >
          <p>I'm a disabled tab!</p>
        </b-tab>
      </b-tabs>
    </div>
  </b-container>
</template>

<script scoped>

import {v4} from "uuid";

export default {
  name: "TrainingPlanEditView",
  props: {
    trainingPlan: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      display: 'Clone',
      activeTabIndex: 0,
      maxTabIndex: 1,
      selectedExercise: null,
      selectedDevice: null,
      origTrainingPlan: this.trainingPlan
    }
  },
  computed: {
    sortTrainingPlanExercises() {
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      return this.origTrainingPlan.trainingPlanXExercises.sort(
        (a, b) => { // sort using this.orderBy
          if (null === a
            || null === b
          ) {
            return 0;
          }

          const first = a.order
          const next = b.order
          if (first > next) {
            return 1
          }
          if (first < next) {
            return -1
          }
          return 0
        }
      );
    },
    possibleDevices() {
      return this.$store.getters['devices/devices'];
    },
    possibleExercises() {
      return this.$store.getters['exercises/exercises'];
    },
    possibleExerciseOptions() {
      return this.$store.getters['exerciseOptions/exerciseOptions'];
    },
    possibleDeviceOptions() {
      return this.$store.getters['deviceOptions/deviceOptions'];
    },
    possibleExerciseOptionsWithFilteredValues() {
      if (!this.selectedExercise) {
        return [];
      }

      let finalExerciseOptions = [];
      let preparedPossibleOptions = {};

      this.possibleExerciseOptions.forEach(exerciseOption => {
        preparedPossibleOptions[exerciseOption.id] = exerciseOption;
      });

      console.debug(this.selectedTrainingPlanExerciseOptions);
      console.debug(preparedPossibleOptions);

      for (let key in this.selectedTrainingPlanExerciseOptions) {
        if (undefined !== preparedPossibleOptions[key]
          && null !== this.selectedTrainingPlanExerciseOptions[key]
          && this.selectedTrainingPlanExerciseOptions[key].length
        ) {
          finalExerciseOptions.push({
            value: this.selectedTrainingPlanExerciseOptions[key],
            exerciseOption: preparedPossibleOptions[key]
          });
        }
      }

      return finalExerciseOptions;
    },
    possibleDeviceOptionsWithFilteredValues() {
      if (!this.selectedDevice) {
        return [];
      }

      let finalDeviceOptions = [];
      let preparedPossibleOptions = {};

      this.possibleDeviceOptions.forEach(deviceOption => {
        preparedPossibleOptions[deviceOption.id] = deviceOption;
      });

      for (let key in this.selectedTrainingPlanDeviceOptions) {
        if (undefined !== preparedPossibleOptions[key]
          && this.selectedTrainingPlanDeviceOptions[key].length
        ) {
          finalDeviceOptions.push({
            value: this.selectedTrainingPlanDeviceOptions[key],
            deviceOption: preparedPossibleOptions[key]
          });
        }
      }

      return finalDeviceOptions;
    }
  },
  methods: {
    onExercisesMove(event) {
      let newIndex = 0;
      let oldIndex = 0;

      console.log("reorderExercises");
      console.log(this.origTrainingPlan.id);
      window.console.log(event);
      window.console.log(this);

      if (event.added) {
        this.$parent.newIndex = event.added.newIndex;
        this.$parent.exerciseMoveTarget = this;
        return;
      } else if (event.removed) {
        newIndex = this.$parent.newIndex;
        oldIndex = event.removed.oldIndex;
        //        this.$parent.exerciseMoveTarget.origTrainingPlanExercises.splice(newIndex, 0, this.origTrainingPlanExercises.splice(oldIndex, 1)[0]);
        this.$parent.exerciseMoveTarget.trainingPlan.trainingPlanXExercises.forEach(function(item, index) {
          item.order = index;
        });
        this.$parent.newIndex = 0;
      } else if (event.moved) {
        oldIndex = event.moved.oldIndex;
        newIndex = event.moved.newIndex;
        this.origTrainingPlan.trainingPlanXExercises.splice(newIndex, 0, this.origTrainingPlan.trainingPlanXExercises.splice(oldIndex, 1)[0]);
        this.origTrainingPlan.trainingPlanXExercises.forEach(function(item, index) {
          item.order = index;
        });
      }
    },
    onExerciseUpdate(event) {
      window.console.log(event);
    },
    onExerciseClone(event) {
      window.console.log(event);
    },
    onFormWizardChanged(prevIndex, currentIndex) {
      this.activeTabIndex = 0 <= currentIndex ? currentIndex : 1;
      let maxTabIndex = this.$refs.formWiz.tabs.length - 1;
      this.maxTabIndex = 0 < maxTabIndex ? maxTabIndex : 1;
    },
    addExercise() {
      console.log(this.$refs.formWiz);

      let preparedPossibleExerciseOptions = {};
      let preparedPossibleDeviceOptions = {};
      let preparedExistingTrainingPlanExerciseOptions = {};

      this.possibleExerciseOptions.forEach(exerciseOption => {
        preparedPossibleExerciseOptions[exerciseOption.id] = exerciseOption;
      });

      this.possibleDeviceOptions.forEach(deviceOption => {
        preparedPossibleDeviceOptions[deviceOption.id] = deviceOption;
      });

      console.log(preparedExistingTrainingPlanExerciseOptions);

      if (null !== this.currentTrainingPlanExercise) {
        this.currentTrainingPlanExercise.trainingPlanXExerciseOptions.forEach((trainingPlanXExerciseOption, key) => {
          preparedExistingTrainingPlanExerciseOptions[trainingPlanXExerciseOption.exerciseOption.id] = trainingPlanXExerciseOption;
          preparedExistingTrainingPlanExerciseOptions[trainingPlanXExerciseOption.exerciseOption.id].pos = key;
        });
      }

      let isNewTrainingPlanExercise = false;
      if (null === this.currentTrainingPlanExercise) {
        this.currentTrainingPlanExercise = this.generateTrainingPlanExercise();
        isNewTrainingPlanExercise = true;
      }

      for (let key in this.selectedTrainingPlanExerciseOptions) {
        if (undefined !== preparedExistingTrainingPlanExerciseOptions[key]) {
          this.currentTrainingPlanExercise.trainingPlanXExerciseOptions[preparedExistingTrainingPlanExerciseOptions[key].pos].optionValue = this.selectedTrainingPlanExerciseOptions[key];
        } else if (undefined !== preparedPossibleExerciseOptions[key]
          && this.selectedTrainingPlanExerciseOptions[key].length
        ) {
          this.currentTrainingPlanExercise.trainingPlanXExerciseOptions.push(
            this.generateTrainingPlanXExerciseOption(
              this.currentTrainingPlanExercise,
              preparedPossibleExerciseOptions[key],
              this.selectedTrainingPlanExerciseOptions[key]
            )
          )
        }
      }

      if (true === isNewTrainingPlanExercise) {
        // hier muss noch geprüft werden ob diese trainingplanexercise bereits vorhanden ist!
        this.origTrainingPlan.trainingPlanXExercises.push(this.currentTrainingPlanExercise);
      }

      this.cancel();
    },
    // function considers possible changed exercise with existing in current trainingplan
    considerCurrentSelectedExercise() {
      if (this.currentTrainingPlanExercise
        && undefined !== this.currentTrainingPlanExercise.exercise
        && this.currentTrainingPlanExercise.exercise.id !== this.selectedExercise.id
      ) {
        this.currentTrainingPlanExercise.exercise = this.selectedExercise;
      }
    },
    generateTrainingPlanExercise() {
      let trainingPlanExercise = {};
      trainingPlanExercise.exercise = this.selectedExercise;
      trainingPlanExercise.id = v4();
      trainingPlanExercise.order = 0;
      trainingPlanExercise.remark = '';
      trainingPlanExercise.trainingPlan = '/api/training_plan/'+this.id;
      trainingPlanExercise.trainingPlanXExerciseOptions = [];

      return trainingPlanExercise;
    },
    generateTrainingPlanXExerciseOption(trainingPlanExercise, exerciseOption, value) {
      let trainingPlanXExerciseOption = {};
      trainingPlanXExerciseOption.exerciseOption = exerciseOption;
      trainingPlanXExerciseOption.id = v4();
      trainingPlanXExerciseOption.optionValue = value;
      trainingPlanXExerciseOption.trainingPlanXExercise = null !== trainingPlanExercise.id ? '/api/training_plan_x_exercises/'+trainingPlanExercise.id : v4();

      return trainingPlanXExerciseOption;
    },
    onComplete() {

    },
    selectExercise(exercise) {
      if (this.selectedExercise === exercise) {
        this.selectedExercise = null;
        this.selectedDevice = null;
      } else {
        this.selectedExercise = exercise
        this.selectedDevice = exercise.exerciseXDevice.device;
      }
    },
    saveDevice(device) {
      if (this.selectedDevice === device) {
        this.selectedDevice = null;
      } else {
        this.selectedDevice = device;
      }
    },
    editTrainingPlanExercise(trainingPlanExercise) {
      console.log("EDIT TRAININGPLAN EXERCISE!");
      this.selectedTrainingPlanExerciseOptions = {};

      let preparedCurrentExerciseOptions = {};
      trainingPlanExercise.exercise.exerciseXExerciseOptions.forEach(option => {
        preparedCurrentExerciseOptions[option.exerciseOption.id] = option;
      });

      this.$store.getters['exerciseOptions/exerciseOptions'].forEach(option => {
        if (undefined !== preparedCurrentExerciseOptions[option.id]) {
          this.selectedTrainingPlanExerciseOptions[option.id] = {value: preparedCurrentExerciseOptions[option.id].optionValue};
        } else {
          this.selectedTrainingPlanExerciseOptions[option.id] = {value: null};
        }
      });

      console.log("SETZE EXERCISE!");

      this.currentTrainingPlanExercise = trainingPlanExercise;
      this.selectedExercise = trainingPlanExercise.exercise;
      this.selectedDevice = null !== trainingPlanExercise.exercise.exerciseXDevice ? trainingPlanExercise.exercise.exerciseXDevice.device : null;
      this.currentTrainingPlanExerciseOptions = trainingPlanExercise.trainingPlanXExerciseOptions;

      this.showModal();
    },
    showModal() {
      this.$root.$emit('bv::show::modal', 'modal-'+this.origTrainingPlan.id, '#btnShow')
    },
    hideModal() {
      this.$root.$emit('bv::hide::modal', 'modal-'+this.origTrainingPlan.id, '#btnShow')
    },
    toggleModal() {
      this.$root.$emit('bv::toggle::modal', 'modal-'+this.origTrainingPlan.id, '#btnToggle')
    },
    back() {
      this.$refs.formWiz.prevTab();
    },
    next() {
      this.$refs.formWiz.nextTab();
    },
    cancel() {
      //      this.selectedTrainingPlanExerciseOptions = {};
      //      this.selectedTrainingPlanDeviceOptions = {};
      this.currentTrainingPlanExercise = null;
      this.$refs.formWiz.reset();

      this.hideModal();
    },
    retrieveExerciseOptionValue(exerciseOption) {
      this.selectedTrainingPlanExerciseOptions.forEach(trainingPlanExerciseOption => {
        if (trainingPlanExerciseOption.exerciseOption.id === exerciseOption.id
          && trainingPlanExerciseOption.optionValue.length
        ) {
          return trainingPlanExerciseOption.optionValue;
        }
      });
      if (exerciseOption.exerciseOptionValue
        && undefined !== exerciseOption.exerciseOptionValue
      ) {
        return exerciseOption.exerciseOptionValue;
      } else if (undefined !== exerciseOption.exerciseOption
        && undefined !== exerciseOption.exerciseOption.defaultValue
        && exerciseOption.exerciseOption.defaultValue.length
        && !this.checkIsMultipartOption(exerciseOption.exerciseOption.defaultValue)
      ) {
        return exerciseOption.exerciseOption.defaultValue;
      }
      return 'None';
    },
    retrieveDeviceOptionValue(deviceOption) {
      this.selectedTrainingPlanDeviceOptions.forEach(trainingPlanDeviceOption => {
        if (trainingPlanDeviceOption.deviceOption.id === deviceOption.id
          && trainingPlanDeviceOption.optionValue.length
        ) {
          return trainingPlanDeviceOption.optionValue;
        }
      });
      if (deviceOption.deviceOptionValue
        && undefined !== deviceOption.deviceOptionValue
      ) {
        return deviceOption.deviceOptionValue;
      } else if (undefined !== deviceOption.deviceOption
        && undefined !== deviceOption.deviceOption.defaultValue
        && deviceOption.deviceOption.defaultValue.length
        && !this.checkIsMultipartOption(deviceOption.deviceOption.defaultValue)
      ) {
        return deviceOption.deviceOption.defaultValue;
      }
      return 'None';
    },
    checkIsMultipartOption(value) {
      const regex = /\|/;
      let regexResult = regex.exec(value);
      let result = regexResult && 0 < regexResult.length

      return result ? true : false;
    }
  }
};
</script>