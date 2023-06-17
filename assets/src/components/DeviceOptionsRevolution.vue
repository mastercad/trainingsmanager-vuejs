<template>
  <div class="flex-grid">
    <div
      v-for="deviceOption in prepareOptions"
      :id="deviceOption.key"
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
          :key="option.key"
          :active="option.isActive"
          @click="saveSelection(option, deviceOption)"
        >
          {{ option.value }}
        </b-dropdown-item>
      </b-dropdown>
      <div v-else>
        <span>{{ deviceOption.name }}</span>:
        <input
          class="form-control"
          :placeholder="deviceOption.placeholder"
          type="text"
          :value="deviceOption.value"
          @input="saveValue($event.target.value, deviceOption)"
        >
      </div>
    </div>
  </div>
</template>

<script>
import deviceOptions from '../controllers/deviceOptions.js';
import devices from '../controllers/devices.js';
import OptionFunctions from "../shared/optionFunctions.js";

export default {
  name: "DeviceOption",
  alias: "device-option",
  props: {
    possibleDeviceOptions: {
      type: Array,
      required: true
    },
    selectedDevice: {
      type: Object,
      required: true
    },
    existingDeviceOptions: {
      type: Array,
      default: () => { return new Array(); }
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
      currentSelectedDevice: this.selectedDevice,
      origCurrentTrainingPlanDeviceOptions: this.currentTrainingPlanDeviceOptions
    };
  },
  computed: {
    prepareOptions () {
      console.log("PREPARE OPTIONS!");
      let resultOptions = [];
      let selectedTrainingPlanDeviceOptions = this.$store.getters["trainingPlanExerciseOptions/getSelectedTrainingPlanExerciseDeviceOptions"];

      for (const key in this.possibleDeviceOptions) {
        let possibleOption = this.possibleDeviceOptions[key];
        let value = possibleOption.defaultValue;
        let selectedValue = selectedTrainingPlanDeviceOptions[possibleOption.id] ? selectedTrainingPlanDeviceOptions[possibleOption.id].value : null;

        let name = possibleOption.name;
        let isMultipartOption = OptionFunctions.isMultipartOption(value);
        let outerKey = this.selectedDevice.id+'_'+possibleOption.id;
        let optionInformation = {
          key: outerKey,
          name: name,
          isMultipartOption: isMultipartOption,
          origOption: possibleOption,
          bindKey: outerKey+'_'+selectedValue,
          value: selectedValue,
        };

        if (isMultipartOption) {
          optionInformation.parts = OptionFunctions.splitOption(value);
          optionInformation.parts.forEach( (partValue, index) => {
            let key = OptionFunctions.generateOptionPartKey(this.selectedDevice.id, possibleOption.id, index);
            let isActive = partValue == selectedValue;

            if (isActive) {
              optionInformation.name = optionInformation.name + " ("+selectedValue+")";
            }

            optionInformation.parts[index] = {
              isActive: isActive,
              key: key,
              value: partValue
            };
          });
        } else {
          optionInformation.placeholder = value;
        }

        resultOptions.push(optionInformation);
      }

      return resultOptions;
    }
  },
  methods: {
    saveSelection(option, preparedDeviceOption) {
      this.$store.dispatch("trainingPlanExerciseOptions/updateSelectedTrainingPlanExerciseDeviceOption", {
        id: preparedDeviceOption.origOption.id,
        trainingPlanXDeviceId: preparedDeviceOption.id,
        value: option.value,
        isDefault: false,
        name: option.value.trim().length ? preparedDeviceOption.origOption.name+' ('+option.value+')' : preparedDeviceOption.origOption.name,
        origName: preparedDeviceOption.origOption.name
      });
    },
    saveValue(value, preparedDeviceOption) {
      this.$store.dispatch("trainingPlanExerciseOptions/updateSelectedTrainingPlanExerciseDeviceOption", {
        id: preparedDeviceOption.origOption.id,
        trainingPlanXDeviceId: preparedDeviceOption.id,
        value: value,
        isDefault: false,
        name: preparedDeviceOption.origOption.name,
        origName: preparedDeviceOption.origOption.name
      });
    }
  }
}
</script>
