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
        :key="origId+'_'+trainingPlanExercise.exercise.name+'_'+trainingPlanExercise.order"
        class="list-group-item exercise-sort-item"
      >
        {{ trainingPlanExercise.exercise.name }}
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
                  @click="saveExercise(exercise)"
                >
                  {{ exercise.name }}
                </li>
              </ul>
            </div>
            <div
              v-if="selectedExercise"
              class="col-6 border rounded list-group p-2"
            >
              <exercise-options
                :key="selectedExercise ? 'exercise_'+selectedExercise.id : 'unselected'"
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
              <device-options
                :key="selectedDevice ? 'device_'+selectedDevice.id : 'unselected'"
                :selected-device="selectedDevice"
                :selected-exercise="selectedExercise"
                :selected-training-plan-device-options="selectedTrainingPlanDeviceOptions"
                :existing-device-options="selectedExercise.exerciseXDeviceOptions"
                :possible-device-options="possibleDeviceOptions"
              />
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
          @click="save()"
        >
          Add
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script scoped>
import draggable from 'vuedraggable';
import ExerciseOptions from './ExerciseOptions.vue';
import DeviceOptions from './DeviceOptions.vue';

export default {
  name: "TrainingPlanView",
  components: {
    draggable: draggable,
    ExerciseOptions,
    DeviceOptions
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
      orderBy: 'order',
      activeTabIndex: 0,
      maxTabIndex: 1,
      selectedExercise: null,
      selectedDevice: null,
      selectedTrainingPlanExerciseOptions: new Array(),
      selectedTrainingPlanDeviceOptions: new Array(),
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
    sortTrainingPlanExercises() {
      return this.origTrainingPlanExercises.sort(
        (a, b) => { // sort using this.orderBy
          const first = a[this.orderBy]
          const next = b[this.orderBy]
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
      let exerciseOptions = this.selectedTrainingPlanExerciseOptions ? this.selectedTrainingPlanExerciseOptions : [];
      exerciseOptions = this.selectedExercise.exerciseXExerciseOptions;
      let finalExerciseOptions = [];
      exerciseOptions.forEach(exerciseOption => {
        let value = this.retrieveExerciseOptionValue(exerciseOption);
        let finalExerciseOption = exerciseOption;
        if (value.length
          && 'None' !== value
        ) {
          finalExerciseOption['value'] = value;
          finalExerciseOptions.push(finalExerciseOption);
        }
      });
      return finalExerciseOptions;
    },
    possibleDeviceOptionsWithFilteredValues() {
      if (!this.selectedDevice) {
        return [];
      }
      let deviceOptions = this.selectedExerciseDeviceOptions ? this.selectedExerciseDeviceOptions : [];
      deviceOptions = this.selectedExercise.exerciseXDeviceOptions;
      let finalDeviceOptions = [];
      deviceOptions.forEach(deviceOption => {
        let value = this.retrieveDeviceOptionValue(deviceOption);
        let finalDeviceOption = deviceOption;
        if (value.length
          && 'None' !== value
        ) {
          finalDeviceOption['value'] = value;
          finalDeviceOptions.push(finalDeviceOption);
        }
      });
      return finalDeviceOptions;
    }
  },
  methods: {
    onExercisesMove(event) {
      let newIndex = 0;
      let oldIndex = 0;

      console.log("reorderExercises");
      console.log(this.origId);
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
    addExercise() {
      this.origTrainingPlanExercises.push(
        {
          exercise: {
            name: 'TEST EXERCISE!',
            description: '',
            previewPicturePath: '',
            seoLing: '',
            specialFeatures: ''
          },
          order: this.origTrainingPlanExercises.length,
          remark: '',
          trainingPlan: this.origId
        }
      )
    },
    onComplete() {

    },
    saveExercise(exercise) {
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
    showModal() {
      this.$root.$emit('bv::show::modal', 'modal-'+this.origId, '#btnShow')
    },
    hideModal() {
      this.$root.$emit('bv::hide::modal', 'modal-'+this.origId, '#btnShow')
    },
    toggleModal() {
      this.$root.$emit('bv::toggle::modal', 'modal-'+this.origId, '#btnToggle')
    },
    back() {
      this.$refs.formWiz.prevTab();
    },
    next() {
      this.$refs.formWiz.nextTab();
    },
    cancel() {
      this.selectedExercise = null;
      this.selectedDevice = null;
      this.$refs.formWiz.reset();

      this.hideModal();
    },
    save() {
      console.log(this.$refs.formWiz);

      this.cancel();
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
    },
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
