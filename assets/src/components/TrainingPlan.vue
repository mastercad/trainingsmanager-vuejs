<template>
  <div class="row p-2 border rounded">
    <div class="row">
      <div class="col-11">
        <h1>{{ trainingPlan.name }}</h1>
      </div>
      <div class="col-1">
        <b-button v-b-modal.modal="'modal-'+trainingPlan.id">
          <font-awesome-icon icon="fa-solid fa-plus" />
        </b-button>
      </div>
    </div>

    <draggable
      v-if="trainingPlan.trainingPlanXExercises"
      :list="trainingPlan.trainingPlanXExercises"
      class="list-group"
      ghost-class="ghost"
      :options="{group:'tags'}"
      :group="{ name: 'g1' }"
      :transition-mode="true"

      @change="onExercisesMove"
      @move="onExercisesMove"
      @update="onExerciseUpdate"
      @clone="onExerciseClone"
    >
      <div
        v-for="trainingPlanExercise in sortTrainingPlanExercises"
        v-if="trainingPlanExercise"
        :key="trainingPlan.id+'_'+trainingPlanExercise.exercise.name+'_'+trainingPlanExercise.order"
        class="list-group-item exercise-sort-item"
      >
        {{ trainingPlanExercise.exercise.name }}
        <span @click="editTrainingPlanExercise(trainingPlanExercise);">Edit</span>
      </div>
    </draggable>

    <b-modal
      :id="'modal-'+trainingPlan.id"
      ref="formWizModal"
      title="Exercise Wizard"
      scrollable
      static
      size="xl"
    >
      <form-wizard
        ref="formWiz"
        scope="props"
        class="w-full sm:float-right relative max-w-md bg-white shadow-lg rounded overflow-hidden"
        shape="circle"
        back-button-text="Back"
        next-button-text="Next"
        finish-button-text="Add"
        @on-complete="onComplete"
        @on-change="onFormWizardChanged"
      >
        <div slot="title" />
        <tab-content
          :id="0"
          ref="exerciseTab"
          title="Exercise"
          :on-loading="onFormWizardChanged"
        >
          <h2 class="text-xl text-gray-800 text-center mb-2">
            Choose Exercise
            <br>
            <span
              v-if="trainingPlan.trainingPlanExercises && trainingPlan.trainingPlanExercises[0]"
              class="fs-6"
            >
              ({{ trainingPlan.trainingPlanExercises[0].name }})
            </span>
          </h2>

          <div class="row">
            <div class="col-6">
              <ul class="list-group">
                <li
                  v-for="exercise in possibleExercises"
                  :key="'exercise-'+exercise.id"
                  class="list-group-item"
                  :class="{ active: selectedExercise && exercise.id === selectedExercise.id }"
                  @click="selectExercise(exercise)"
                >
                  {{ exercise.name }}
                </li>
              </ul>
            </div>

            <div
              v-if="selectedExercise"
              class="col-6 border rounded list-group p-2"
            >
              <options-for-edit
                type="exercise"
                :possible-options="possibleExerciseOptions"
                :current-options="currentTrainingPlanExercise && currentTrainingPlanExercise.exerciseXExerciseOptions ? trainingPlanExercise.exerciseXExerciseOptions : []"
                :additional-options="selectExercise.exerciseXExerciseOptions"
                :identifier="selectedExercise.id"
                description="Possible Exercise Options for this Exercise"
              >
              </options-for-edit>

              <div class="col-12">
                {{ selectedExercise.description }}
              </div>

              <div class="col-12">
                {{ selectedExercise.specialFeatures }}
              </div>
            </div>
          </div>
        </tab-content>

        <tab-content
          :id="1"
          ref="deviceTab"
          title="Device"
        >
          <h2 class="text-xl text-gray-800 text-center mb-2">
            Choose Device
            <br>
            <span
              v-if="selectedDevice"
              class="fs-6"
            >
              ({{ selectedDevice.name }})
            </span>
          </h2>
          <div class="row">
            <div class="col-6">
              <ul class="list-group">
                <li
                  v-for="device in possibleDevices"
                  :key="'device-'+device.id"
                  class="list-group-item"
                  :class="{ active: selectedDevice && device.id === selectedDevice.id }"
                  @click="saveDevice(device)"
                >
                  {{ device.name }}
                </li>
              </ul>
            </div>
            <div
              v-if="selectedDevice"
              class="col-6 border rounded list-group p-2"
            >
              <options-for-edit
                type="device"
                :possible-options="possibleDeviceOptions"
                :current-options="selectedExercise.exerciseXDeviceOptions ? selectedExercise.exerciseXDeviceOptions : []"
                :additional-options="selectedExercise.exerciseXDevices[0] ? selectedExercise.exerciseXDevices[0].device.deviceXDeviceOptions : []"
                :identifier="selectedDevice.id"
                description="Possible Device Options for this Device"
              >
              </options-for-edit>
            </div>
          </div>
        </tab-content>

        <tab-content
          :id="2"
          ref="finishTab"
          title="Add"
        >
          <h2 class="text-xl text-gray-800 text-center mb-2">Add Exercise</h2>
          <b>Configuration:</b>
          <div
            v-if="selectedExercise"
            class="row"
          >
            <div class="col-6">
              Exercise: <span v-if="selectedExercise">{{ selectedExercise.name }}</span>
            </div>
            <div class="col-6">
              <ul>
                <li
                  v-for="(possibleExerciseOption, index) in possibleExerciseOptionsWithFilteredValues"
                  :key="'possible_exercise_option_'+index"
                >
                  {{ possibleExerciseOption.exerciseOption.name }}: {{ possibleExerciseOption.value }}
                </li>
              </ul>
            </div>
          </div>
          <div
            v-if="selectedDevice"
            class="row"
          >
            <div
              class="col"
            >
              Device: {{ selectedDevice.name }}
            </div>
            <div class="col-6">
              <ul>
                <li
                  v-for="(possibleDeviceOption, index) in possibleDeviceOptionsWithFilteredValues"
                  :key="'possible_device_option_'+index"
                >
                  {{ possibleDeviceOption.deviceOption.name }}: {{ possibleDeviceOption.value }}
                </li>
              </ul>
            </div>
          </div>
        </tab-content>
        <div slot="footer" />
      </form-wizard>

      <template
        #modal-footer="{}"
      >
        <b-button
          v-if="0 < activeTabIndex"
          size="lg"
          variant="danger"
          @click="back()"
        >
          Back
        </b-button>
        <b-button
          size="lg"
          variant="outline-secondar"
          @click="cancel()"
        >
          Cancel
        </b-button>
        <b-button
          v-if="activeTabIndex < maxTabIndex"
          size="lg"
          variant="danger"
          @click="next()"
        >
          Next
        </b-button>
        <b-button
          v-if="activeTabIndex == maxTabIndex"
          size="lg"
          variant="primary"
          @click="addExercise()"
        >
          Add
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script scoped>
import draggable from 'vuedraggable';
import OptionsForEdit from './OptionsForEdit.vue';

import IdGenerator from '../shared/idGenerator';

export default {
  name: "TrainingPlanView",
  components: {
    draggable: draggable,
    OptionsForEdit
  },
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
      currentTrainingPlanExercise: null
    }
  },
  computed: {
    sortTrainingPlanExercises() {
      return this.trainingPlan.trainingPlanXExercises.sort(
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
      console.log(this.trainingPlan.id);
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
        this.trainingPlan.trainingPlanXExercises.splice(newIndex, 0, this.trainingPlan.trainingPlanXExercises.splice(oldIndex, 1)[0]);
        this.trainingPlan.trainingPlanXExercises.forEach(function(item, index) {
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
      let preparedExistingTrainingPlanExerciseXDeviceOptions = {};

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
        this.trainingPlan.trainingPlanXExercises.push(this.currentTrainingPlanExercise);
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
      trainingPlanExercise.id = IdGenerator.generate('training_plan_x_exercise_');
      trainingPlanExercise.order = 0;
      trainingPlanExercise.remark = '';
      trainingPlanExercise.trainingPlan = '/api/training_plan/'+this.id;
      trainingPlanExercise.trainingPlanXExerciseOptions = [];
      trainingPlanExercise.trainingPlanXDeviceOptions = [];
      trainingPlanExercise.trainingPlan = this.trainingPlan;

      return trainingPlanExercise;
    },
    generateTrainingPlanXExerciseOption(trainingPlanExercise, exerciseOption, value) {
      let trainingPlanXExerciseOption = {};
      trainingPlanXExerciseOption.exerciseOption = exerciseOption;
      trainingPlanXExerciseOption.id = IdGenerator.generate('training_plan_x_exercise_option_');
      trainingPlanXExerciseOption.optionValue = value;
      trainingPlanXExerciseOption.trainingPlanXExercise = null !== trainingPlanExercise.id ? '/api/training_plan_x_exercises/'+trainingPlanExercise.id : null;

      return trainingPlanXExerciseOption;
    },
    generateTrainingPlanXDeviceOption(trainingPlanExercise, deviceOption, value) {
      let trainingPlanXDeviceOption = {};
      trainingPlanXDeviceOption.deviceOption = deviceOption;
      trainingPlanXDeviceOption.id = IdGenerator.generate('training_plan_x_device_option_');
      trainingPlanXDeviceOption.optionValue = value;
      trainingPlanXDeviceOption.trainingPlanXExercise = null !== trainingPlanExercise.id ? '/api/training_plan_x_exercises/'+trainingPlanExercise.id : null;

      return trainingPlanXDeviceOption;
    },
    onComplete() {
      console.log("ON COMPLETE!");
    },
    selectExercise(exercise) {
      if (this.selectedExercise === exercise) {
        this.trainingPlanExercise.exercise = null;
      } else {
        this.trainingPlanExercise.exercise = exercise;
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
      this.currentTrainingPlanExerciseOptions = trainingPlanExercise.trainingPlanXExerciseOptions;

      this.showModal();
    },
    showModal() {
      this.$root.$emit('bv::show::modal', 'modal-'+this.trainingPlan.id, '#btnShow')
    },
    hideModal() {
      this.$root.$emit('bv::hide::modal', 'modal-'+this.trainingPlan.id, '#btnShow')
    },
    toggleModal() {
      this.$root.$emit('bv::toggle::modal', 'modal-'+this.trainingPlan.id, '#btnToggle')
    },
    back() {
      this.$refs.formWiz.prevTab();
    },
    next() {
      this.$refs.formWiz.nextTab();
    },
    cancel() {
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

<style scoped>
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
  transition: all 0.7s ease-out;
}
</style>
