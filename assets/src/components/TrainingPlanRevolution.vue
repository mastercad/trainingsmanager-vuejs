<template>
  <div class="row p-2 border rounded">
    <div class="row">
      <div class="col-11">
        <h1>{{ origName }}</h1>
      </div>
      <div class="col-1">
        <b-button v-b-modal.modal="'modal-'+origId">
          <font-awesome-icon icon="fa-solid fa-plus" />
        </b-button>
      </div>
    </div>

    <draggable
      v-if="origTrainingPlanExercises"
      :list="origTrainingPlanExercises"
      class="list-group"
      ghost-class="ghost"
      :options="{ group: 'tags' }"
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
        :key="origId+'_'+trainingPlanExercise.exercise.name+'_'+trainingPlanExercise.order"
        class="list-group-item exercise-sort-item"
      >
        {{ trainingPlanExercise.exercise.name }}
        <span @click="editTrainingPlanExercise(trainingPlanExercise);">Edit</span>
      </div>
    </draggable>

    <b-modal
      :id="'modal-'+origId"
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
              v-if="selectedExercise"
              class="fs-6"
            >
              ({{ selectedExercise.name }})
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
              <exercise-options-revolution
                :selected-exercise="selectedExercise"
                :selected-training-plan-exercise-options="selectedTrainingPlanExerciseOptions"
                :existing-exercise-options="selectedExercise.exerciseXExerciseOptions"
                :possible-exercise-options="possibleExerciseOptions"
              />

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
                  @click="selectDevice(device)"
                >
                  {{ device.name }}
                </li>
              </ul>
            </div>
            <div
              v-if="selectedDevice"
              class="col-6 border rounded list-group p-2"
            >
              <device-options-revolution
                :key="deviceOptionKey"
                :selected-device="selectedDevice"
                :selected-exercise="selectedExercise"
                :existing-device-options="selectedExercise.exerciseXDeviceOptions"
                :possible-device-options="possibleDeviceOptions"
              />
            </div>
          </div>
        </tab-content>
        <tab-content
          :id="2"
          :key="exerciseOptionKey+'_'+deviceOptionKey"
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
                  v-for="(possibleExerciseOption, index) in selectedTrainingPlanExerciseOptions"
                  v-if="possibleExerciseOption.value || possibleExerciseOption.defaultValue"
                  :key="'possible_exercise_option_'+index"
                >
                  {{ possibleExerciseOption.origName }}: {{ possibleExerciseOption.value ? possibleExerciseOption.value : possibleExerciseOption.defaultValue }}
                </li>
              </ul>
            </div>
          </div>
          <div
            v-if="selectedDevice"
            class="row"
          >
            <div class="col-6">
              Device: {{ selectedDevice.name }}
            </div>
            <div class="col-6">
              <ul>
                <li
                  v-for="(possibleDeviceOption, index) in selectedTrainingPlanDeviceOptions"
                  v-if="possibleDeviceOption.value || possibleDeviceOption.defaultValue"
                  :key="'possible_device_option_'+index"
                >
                  {{ possibleDeviceOption.origName }}: {{ possibleDeviceOption.value ? possibleDeviceOption.value : possibleDeviceOption.defaultValue }}
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
          v-if="activeTabIndex == maxTabIndex && !selectedTrainingPlanExercise"
          size="lg"
          variant="primary"
          @click="addTrainingPlanExercise()"
        >
          Add
        </b-button>
        <b-button
          v-if="activeTabIndex == maxTabIndex && selectedTrainingPlanExercise"
          size="lg"
          variant="primary"
          @click="updateTrainingPlanExercise()"
        >
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script scoped>
import draggable from 'vuedraggable';
import ExerciseOptionsRevolution from './ExerciseOptionsRevolution.vue';
import DeviceOptionsRevolution from './DeviceOptionsRevolution.vue';
import OptionFunctions from "../shared/optionFunctions.js";

import {v4} from "uuid";

export default {
  name: "TrainingPlanView",
  components: {
    draggable: draggable,
    ExerciseOptionsRevolution,
    DeviceOptionsRevolution
  },
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    parent: {
      type: String,
      default: null
    },
    order: {
      type: Number,
      default: 1
    },
    user: {
      type: String,
      required: true
    },
    children: {
      type: Array,
      default: () => []
    },
    trainingPlanExercises: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      display: 'Clone',
      activeTabIndex: 0,
      maxTabIndex: 1,
      selectedExercise: null,
      selectedDevice: null,
      selectedTrainingPlanExercise: null,
      origId: this.id,
      origName: this.name,
      origTrainingPlanLayout: this.trainingPlanLayout,
      origOwner: this.user,
      origParent: this.parent,
      origOrder: this.order,
      origChildren: this.children,
      origTrainingPlanExercises: this.trainingPlanExercises
    }
  },
  computed: {
    exerciseOptionKey() {
      return this.$store.getters['trainingPlanExerciseOptions/exerciseOptionKey'];
    },
    deviceOptionKey() {
      return this.$store.getters['trainingPlanExerciseOptions/deviceOptionKey'];
    },
    sortTrainingPlanExercises() {
      return this.origTrainingPlanExercises.sort(
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
    selectedTrainingPlanExerciseOptions() {
      return this.$store.getters['trainingPlanExerciseOptions/getSelectedTrainingPlanExerciseOptions'];
    },
    selectedTrainingPlanDeviceOptions() {
      return this.$store.getters['trainingPlanExerciseOptions/getSelectedTrainingPlanDeviceOptions'];
    },
    possibleDeviceOptions() {
      return this.$store.getters['deviceOptions/deviceOptions'];
    }
  },
  methods: {
    onExercisesMove(event) {
      let newIndex = 0;
      let oldIndex = 0;

      if (event.added) {
        this.$parent.newIndex = event.added.newIndex;
        this.$parent.exerciseMoveTarget = this;
        return;
      } else if (event.removed) {
        newIndex = this.$parent.newIndex;
        oldIndex = event.removed.oldIndex;
//        this.$parent.exerciseMoveTarget.origTrainingPlanExercises.splice(newIndex, 0, this.origTrainingPlanExercises.splice(oldIndex, 1)[0]);
        this.$parent.exerciseMoveTarget.origTrainingPlanExercises.forEach(function(item, index) {
          item.order = index;
        });
        this.$parent.newIndex = 0;
      } else if (event.moved) {
        oldIndex = event.moved.oldIndex;
        newIndex = event.moved.newIndex;
        this.origTrainingPlanExercises.splice(newIndex, 0, this.origTrainingPlanExercises.splice(oldIndex, 1)[0]);
        this.origTrainingPlanExercises.forEach(function(item, index) {
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
    addTrainingPlanExercise() {
      let preparedPossibleExerciseOptions = {};
      let preparedPossibleDeviceOptions = {};
      let preparedExistingTrainingPlanExerciseOptions = {};

      this.possibleExerciseOptions.forEach(exerciseOption => {
        preparedPossibleExerciseOptions[exerciseOption.id] = exerciseOption;
      });

      this.possibleDeviceOptions.forEach(deviceOption => {
        preparedPossibleDeviceOptions[deviceOption.id] = deviceOption;
      });

      if (null !== this.selectedTrainingPlanExercise) {
        this.selectedTrainingPlanExercise.trainingPlanXExerciseOptions.forEach((trainingPlanXExerciseOption, key) => {
          preparedExistingTrainingPlanExerciseOptions[trainingPlanXExerciseOption.exerciseOption.id] = trainingPlanXExerciseOption;
          preparedExistingTrainingPlanExerciseOptions[trainingPlanXExerciseOption.exerciseOption.id].pos = key;
        });
      }

      let isNewTrainingPlanExercise = false;
      if (null === this.selectedTrainingPlanExercise) {
        this.selectedTrainingPlanExercise = this.generateTrainingPlanExercise();
        isNewTrainingPlanExercise = true;
      }

      for (let key in this.selectedTrainingPlanExerciseOptions) {
        if (undefined !== preparedExistingTrainingPlanExerciseOptions[key]) {
          this.selectedTrainingPlanExercise.trainingPlanXExerciseOptions[preparedExistingTrainingPlanExerciseOptions[key].pos].optionValue =
            this.selectedTrainingPlanExerciseOptions[key];
        } else if (undefined !== preparedPossibleExerciseOptions[key]
          && this.selectedTrainingPlanExerciseOptions[key].length
        ) {
          this.selectedTrainingPlanExercise.trainingPlanXExerciseOptions.push(
            this.generateTrainingPlanXExerciseOption(
              this.selectedTrainingPlanExercise,
              preparedPossibleExerciseOptions[key],
              this.selectedTrainingPlanExerciseOptions[key]
            )
          )
        }
      }

      if (true === isNewTrainingPlanExercise) {
        // hier muss noch geprüft werden ob diese trainingplanexercise bereits vorhanden ist (eventuell)!
        this.origTrainingPlanExercises.push(this.selectedTrainingPlanExercise);
      }

      this.cancel();
    },
    updateTrainingPlanExercise() {
      this.handleTrainingPlanExerciseOptionsForSave(
        this.selectedTrainingPlanExercise,
        this.$store.getters['trainingPlanExerciseOptions/getSelectedTrainingPlanExerciseOptions']
      );
    },
    handleTrainingPlanExerciseOptionsForSave(trainingPlanExercise, selectedTrainingPlanExerciseOptions) {

      this.$store.dispatch("trainingPlanExerciseOptions/update", {
        trainingPlanXExerciseOption: selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption,
        optionValue: selectedTrainingPlanExerciseOption.value,
        exerciseOption: selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption.exerciseOption,
        trainingPlanExercise: this.selectedTrainingPlanExercise
      });
      
      for (let key in selectedTrainingPlanExerciseOptions) {
        let selectedTrainingPlanExerciseOption = selectedTrainingPlanExerciseOptions[key];
        console.log(selectedTrainingPlanExerciseOption);
        // create new trainingPlanExerciseOption
        if (null === selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && undefined !== selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && null !== selectedTrainingPlanExerciseOption.value
          && 0 < selectedTrainingPlanExerciseOption.value.trim().length
          && selectedTrainingPlanExerciseOption.value !== selectedTrainingPlanExerciseOption.defaultValue
        ) {
          console.log("CREATE!: ");
        // update trainingPlanExerciseOption
        } else if (null !== selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && undefined !== selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && null !== selectedTrainingPlanExerciseOption.value
          && 0 < selectedTrainingPlanExerciseOption.value.trim().length
          && selectedTrainingPlanExerciseOption.value !== selectedTrainingPlanExerciseOption.defaultValue
          && selectedTrainingPlanExerciseOption.value !== selectedTrainingPlanExerciseOption.origValue
        ) {
          console.log("UPDATE!: ");
          this.$store.dispatch("trainingPlanExerciseOptions/update", {
            trainingPlanXExerciseOption: selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption,
            optionValue: selectedTrainingPlanExerciseOption.value,
            exerciseOption: selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption.exerciseOption,
            trainingPlanExercise: this.selectedTrainingPlanExercise
          });
        // delete trainingPlanExerciseOption
        } else if (null !== selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && undefined !== selectedTrainingPlanExerciseOption.trainingPlanXExerciseOption
          && (
            null === selectedTrainingPlanExerciseOption.value
            || 0 === selectedTrainingPlanExerciseOption.value.trim().length
            || selectedTrainingPlanExerciseOption.value === selectedTrainingPlanExerciseOption.defaultValue
          )
        ) {
          console.log("DELETE!: ");
        }
      }
    },
    // function considers possible changed exercise with existing in current trainingplan
    considerCurrentSelectedExercise() {
      if (this.selectedTrainingPlanExercise
        && undefined !== this.selectedTrainingPlanExercise.exercise
        && this.selectedTrainingPlanExercise.exercise.id !== this.selectedExercise.id
      ) {
        this.selectedTrainingPlanExercise.exercise = this.selectedExercise;
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
        this.selectDevice(exercise.exerciseXDevice.device);
        let currentTrainingPlanExerciseOptions = {};

        if (null !== this.selectedTrainingPlanExercise) {
          for (let key in this.selectedTrainingPlanExercise.trainingPlanXExerciseOptions) {
            let trainingPlanXExerciseOption = this.selectedTrainingPlanExercise.trainingPlanXExerciseOptions[key];
            currentTrainingPlanExerciseOptions[trainingPlanXExerciseOption.exerciseOption.id] = trainingPlanXExerciseOption;
          }
        }

        let exerciseOptions = {};
        let possibleExerciseOptions = this.$store.getters['exerciseOptions/exerciseOptions'];
        for (let key in possibleExerciseOptions) {
          let possibleExerciseOption = possibleExerciseOptions[key];
          let defaultValue = !OptionFunctions.isMultipartOption(defaultValue) && 0 < possibleExerciseOption.defaultValue.trim().length;
          let value = currentTrainingPlanExerciseOptions[possibleExerciseOption.id] ? currentTrainingPlanExerciseOptions[possibleExerciseOption.id].optionValue : null;
          let trainingPlanXExerciseOption = currentTrainingPlanExerciseOptions[possibleExerciseOption.id] ? currentTrainingPlanExerciseOptions[possibleExerciseOption.id] : null;

          exerciseOptions[possibleExerciseOption.id] = {
            id: possibleExerciseOption.id,
            isDefault: null === defaultValue || value === defaultValue ? true : false,
            value: value,
            defaultValue: defaultValue,
            name: possibleExerciseOption.name,
            origName: possibleExerciseOption.name,
            origValue: value,
            trainingPlanXExerciseOption: trainingPlanXExerciseOption
          }
        }
        this.$store.dispatch('trainingPlanExerciseOptions/initSelectedTrainingPlanExerciseOptions', exerciseOptions);
      }
    },
    selectDevice(device) {
      console.log(device);
      if (this.selectedDevice === device) {
        this.selectedDevice = null;
      } else {
        this.selectedDevice = device;
        let deviceOptions = {};
        let possibleDeviceOptions = this.$store.getters['deviceOptions/deviceOptions'];
        for (let key in possibleDeviceOptions) {
          let possibleDeviceOption = possibleDeviceOptions[key];
          deviceOptions[possibleDeviceOption.id] = {
            id: possibleDeviceOption.id,
            isDefault: !OptionFunctions.isMultipartOption(possibleDeviceOption.defaultValue)
              && 0 < possibleDeviceOption.defaultValue.trim().length ? true : false,
            value: undefined,
            defaultValue: !OptionFunctions.isMultipartOption(possibleDeviceOption.defaultValue)
              && 0 < possibleDeviceOption.defaultValue.trim().length ?
              possibleDeviceOption.defaultValue : undefined,
            name: possibleDeviceOption.name,
            origName: possibleDeviceOption.name
          }
        }
        this.$store.dispatch('trainingPlanExerciseOptions/initDeviceOptions', deviceOptions);
      }
    },
    editTrainingPlanExercise(trainingPlanExercise) {
      this.selectedExercise = null;
      this.selectedDevice = null;
      this.selectedTrainingPlanExercise = trainingPlanExercise;
      this.selectExercise(trainingPlanExercise.exercise);
//      this.selectDevice(null !== trainingPlanExercise.exercise.exerciseXDevice ? trainingPlanExercise.exercise.exerciseXDevice.device : null);

      this.showModal();
    },
    showModal() {
      this.$root.$emit('bv::show::modal', 'modal-'+this.origId, '#btnShow')
    },
    hideModal() {
      this.selectedDevice = null;
      this.selectedExercise = null;
      this.selectedTrainingPlanExercise = null;
      this.$root.$emit('bv::hide::modal', 'modal-'+this.origId, '#btnShow')
    },
    toggleModal() {
      console.log("TOGGLE MODAL!");
      this.$root.$emit('bv::toggle::modal', 'modal-'+this.origId, '#btnToggle')
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
      this.selectedTrainingPlanExercise = null;
      this.$refs.formWiz.reset();

      this.hideModal();
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
