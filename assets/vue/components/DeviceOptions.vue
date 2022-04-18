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
          @click="saveSelection(option, deviceOption)"
        >
          {{ option.value }}
        </b-dropdown-item>
      </b-dropdown>
      <div v-else>
        <span>{{ deviceOption.name }}</span>:
        <input
          v-model="deviceOption.exerciseXDeviceOption.deviceOptionValue"
          class="form-control"
          :placeholder="deviceOption.placeholder"
          type="text"
        >
      </div>
    </div>
  </div>
</template>

<script>
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
    selectedOptions: {
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
      currentSelectedOptions: this.selectedOptions
    };
  },
  computed: {
    prepareDeviceOptions () {
      this.prepareSelectedOptions();

      return this.createCurrentDeviceOptions();
    }
  },
  methods: {
    createCurrentDeviceOptions() {
      let deviceOptions = [];

      this.currentPossibleDeviceOptions.forEach( (possibleDeviceOption, index) => {
        let value = possibleDeviceOption.defaultValue;
        let name = possibleDeviceOption.name;
        let isMultipartOption = this.checkIsMultipartOption(value);
        let outerKey = this.currentSelectedDevice.id+'_'+possibleDeviceOption.id;
        let optionInformation = {
          key: outerKey,
          name: name,
          isMultipartOption: isMultipartOption,
          bindKey: outerKey+'_'+value
        };

        if (isMultipartOption) {
          optionInformation.parts = this.splitDeviceOption(value);
          optionInformation.parts.forEach( (value, index) => {
            let key = this.generateDeviceOptionPartKey(possibleDeviceOption, index);
            let partId = possibleDeviceOption.id;
            let exerciseXDeviceOption = null;

            if((undefined !== this.currentSelectedOptions[partId])) {
              exerciseXDeviceOption = this.currentSelectedOptions[partId];
            } else {
              exerciseXDeviceOption = this.generateEmptyExerciseXDeviceOption(possibleDeviceOption);
              this.currentSelectedExercise.exerciseXDeviceOptions.push(exerciseXDeviceOption);
              this.currentSelectedOptions[partId] = exerciseXDeviceOption;
            }
            let isActive = (undefined !== this.currentSelectedOptions[partId]) && this.currentSelectedOptions[partId].deviceOptionValue == value;

            if (isActive) {
              optionInformation['name'] = optionInformation['name'] + " ("+value+")";
            }

            optionInformation.parts[index] = {
              isActive: isActive,
              value: value,
              key: key,
              exerciseXDeviceOption: exerciseXDeviceOption
            }
          });
        } else {
          optionInformation.value = (undefined !== this.currentSelectedOptions[possibleDeviceOption.id]) ? this.currentSelectedOptions[possibleDeviceOption.id].deviceOptionValue : null;
          optionInformation.placeholder = possibleDeviceOption.defaultValue;

          let exerciseXDeviceOption = null;

          if(undefined !== this.currentSelectedOptions[possibleDeviceOption.id]) {
            exerciseXDeviceOption = this.currentSelectedOptions[possibleDeviceOption.id];
          } else {
            exerciseXDeviceOption = this.generateEmptyExerciseXDeviceOption(possibleDeviceOption);
            this.currentSelectedOptions[possibleDeviceOption.id] = exerciseXDeviceOption;
            this.currentSelectedExercise.exerciseXDeviceOptions.push(exerciseXDeviceOption);
          }

          optionInformation.exerciseXDeviceOption = exerciseXDeviceOption;
        }

        deviceOptions.push(optionInformation);
      });
      return deviceOptions
    },
    prepareSelectedOptions() {
      this.currentSelectedOptions = {};

      this.currentDeviceOptions.forEach(currentDeviceOption => {
        this.currentSelectedOptions[currentDeviceOption.deviceOption.id] = currentDeviceOption;
      });
    },
    splitDeviceOption(value) {
      return value.split('|');
    },
    saveSelection(option, preparedDeviceOption) {
      preparedDeviceOption.parts.forEach( part => {
        part.isActive = false;
        option.exerciseXDeviceOption.deviceOptionValue = null;
      });

      option.exerciseXDeviceOption.deviceOptionValue = option.value;
      this.currentSelectedOptions[option.exerciseXDeviceOption.deviceOption.id] = option.exerciseXDeviceOption;
      option.isActive = true;
      preparedDeviceOption.bindKey = option.key+'_'+option.value;
    },
    checkDeviceOptionIsSelected(deviceOption, index, value) {
      let key = this.generateDeviceOptionPartKey(deviceOption, index);
      this.currentSelectedOptions[key] = value;
      let result = undefined !== this.currentSelectedOptions[key]
        && this.currentSelectedOptions[key] === value;

      return result;
    },
    checkIsMultipartOption(value) {
      const regex = /\|/;
      let regexResult = regex.exec(value);
      let result = regexResult && 0 < regexResult.length

      return result ? true : false;
    },
    generateDeviceOptionPartKey(deviceOption, index) {
      return this.currentSelectedDevice.id+'_'+deviceOption.id+'_'+index;
    },
    generateEmptyExerciseXDeviceOption(deviceOption) {
      return {
        created: null,
        creator: null,
        device: this.selectedDevice.id,
        deviceOption: deviceOption,
        deviceOptionValue: null,
        id: null,
        updated: null,
        updater: null
      }
    }
  }
}
</script>