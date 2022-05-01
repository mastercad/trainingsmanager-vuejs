<template>
  <div class="flex-grid">
    <div
      v-for="deviceOption in prepareDeviceOptions"
      :id="deviceOption.key"
      v-bind:key="deviceOption.key"
    >
      <b-dropdown
        v-if="deviceOption.isMultipartOption"
        split-variant="outline-primary"
        variant="primary"
        class="m-md-2"
        :text="deviceOption.name"
      >
        <b-dropdown-item
          v-for="option in deviceOption.parts"
          :id="option.key"
          v-bind:key="currentSelectedDevice.id+'_'+option.key"
          :active="option.isActive"
          @click="saveSelection(currentSelectedOptions, option, deviceOption)"
        >
          {{ option.value }}
        </b-dropdown-item>
      </b-dropdown>
      <div v-else>
        <span>{{ deviceOption.name }}</span>:
        <input
          v-model="currentSelectedTrainingPlanDeviceOptions[deviceOption.origOption.id]"
          class="form-control"
          :placeholder="deviceOption.placeholder"
          type="text"
        >
      </div>
    </div>
  </div>
</template>

<script>
import OptionFunctions from "../shared/optionFunctions.js";

export default {
  name: "DeviceOptions",
  alias: "device-options",
  props: {
    possibleDeviceOptions: {
      type: Array,
      required: true
    },
    selectedDevice: {
      type: Object,
      required: true
    },
    selectedExercise: {
      type: Object,
      required: true
    },
    existingDeviceOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    selectedTrainingPlanDeviceOptions: {
      type: Object,
      default: () => { return {}; }
    },
    currentTrainingPlanDeviceOptions: {
      type: Array,
      default: () => { return new Array(); }
    }
  },
  data() {
    return {
      currentDeviceOptions: this.existingDeviceOptions,
      currentPossibleDeviceOptions: this.possibleDeviceOptions,
      currentSelectedExercise: this.selectedExercise,
      currentSelectedDevice: this.selectedDevice,
      currentSelectedOptions: this.selectedOptions,
      origCurrentTrainingPlanDeviceOptions: this.currentTrainingPlanDeviceOptions,
      currentSelectedTrainingPlanDeviceOptions: this.selectedTrainingPlanDeviceOptions,
    };
  },
  computed: {
    prepareDeviceOptions () {
      return OptionFunctions.generateCurrentOptions(
        this.currentSelectedDevice.id,
        this.currentPossibleDeviceOptions,
        this.currentSelectedTrainingPlanDeviceOptions
      );
    }
  },
  methods: {
    saveSelection(option, preparedDeviceOption) {
      preparedDeviceOption.parts.forEach( part => {
        part.isActive = false;
        option.exerciseXDeviceOption.deviceOptionValue = null;
      });

      option.exerciseXDeviceOption.deviceOptionValue = option.value;
      this.currentSelectedTrainingPlanDeviceOptions[option.exerciseXDeviceOption.deviceOptionValue.id] = option.exerciseXDeviceOption;
      option.isActive = true;
      preparedDeviceOption.bindKey = option.key+'_'+option.value;
    }
  }
}
</script>
