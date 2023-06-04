<template>
  <div class="flex-grid">
    <div
      v-for="exerciseOption in prepareOptions"
      :id="exerciseOption.key"
    >
      <b-dropdown
        v-if="exerciseOption.isMultipartOption"
        split-variant="outline-primary"
        variant="primary"
        class="m-md-2"
        :text="exerciseOption.name"
      >
        <b-dropdown-item
          v-for="option in exerciseOption.parts"
          :id="option.key"
          :key="option.key"
          :active="option.isActive"
          @click="saveSelection(option, exerciseOption)"
        >
          {{ option.value }}
        </b-dropdown-item>
      </b-dropdown>
      <div v-else>
        <span>{{ exerciseOption.name }}</span>:
        <input
          class="form-control"
          :placeholder="exerciseOption.placeholder"
          type="text"
          :value="exerciseOption.value"
          @input="saveValue($event.target.value, exerciseOption)"
        >
      </div>
    </div>
  </div>
</template>

<script>
import exerciseOptions from '../controllers/exerciseOptions.js';
import exercises from '../controllers/exercises.js';
import OptionFunctions from "../shared/optionFunctions.js";

export default {
  name: "ExerciseOptions",
  alias: "exercise-options",
  props: {
    possibleExerciseOptions: {
      type: Array,
      required: true
    },
    selectedExercise: {
      type: Object,
      required: true
    },
    existingExerciseOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    currentTrainingPlanExerciseOptions: {
      type: Array,
      default: () => { return new Array(); }
    }
  },
  data() {
    return {
      currentExerciseOptions: this.existingExerciseOptions,
      currentPossibleExerciseOptions: this.possibleExerciseOptions,
      currentSelectedExercise: this.selectedExercise,
      origCurrentTrainingPlanExerciseOptions: this.currentTrainingPlanExerciseOptions
    };
  },
  computed: {
    prepareOptions () {
      console.log("PREPARE OPTIONS!");
      let resultOptions = [];
      let selectedTrainingPlanExerciseOptions = this.$store.getters["trainingPlanExerciseOptions/getSelectedTrainingPlanExerciseOptions"];

      for (const key in this.possibleExerciseOptions) {
        let possibleOption = this.possibleExerciseOptions[key];
        let value = possibleOption.defaultValue;
        let selectedValue = selectedTrainingPlanExerciseOptions[possibleOption.id] ? selectedTrainingPlanExerciseOptions[possibleOption.id].value : null;

        let name = possibleOption.name;
        let isMultipartOption = OptionFunctions.isMultipartOption(value);
        let outerKey = this.selectedExercise.id+'_'+possibleOption.id;
        let optionInformation = {
          key: outerKey,
          name: name,
          isMultipartOption: isMultipartOption,
          origOption: possibleOption,
          bindKey: outerKey+'_'+selectedValue,
          value: selectedValue,
          defaultValue: possibleOption.defaultValue,
          trainingPlanXExerciseOption: selectedTrainingPlanExerciseOptions[possibleOption.id] ? selectedTrainingPlanExerciseOptions[possibleOption.id].trainingPlanXExerciseOption : null
        };

        if (isMultipartOption) {
          optionInformation.parts = OptionFunctions.splitOption(value);
          optionInformation.parts.forEach( (partValue, index) => {
            let key = OptionFunctions.generateOptionPartKey(this.selectedExercise.id, possibleOption.id, index);
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
    saveSelection(option, preparedExerciseOption) {
      this.$store.dispatch("trainingPlanExerciseOptions/updateSelectedTrainingPlanExerciseOption", {
        id: preparedExerciseOption.origOption.id,
        trainingPlanXExerciseOption: preparedExerciseOption.trainingPlanXExerciseOption,
        value: option.value,
        defaultValue: preparedExerciseOption.defaultValue,
        isDefault: option.value == preparedExerciseOption.defaultValue ? true : false,
        name: option.value.trim().length ? preparedExerciseOption.origOption.name+' ('+option.value+')' : preparedExerciseOption.origOption.name,
        origName: preparedExerciseOption.origOption.name
      });
    },
    saveValue(value, preparedExerciseOption) {
      console.log(preparedExerciseOption);
      this.$store.dispatch("trainingPlanExerciseOptions/updateSelectedTrainingPlanExerciseOption", {
        id: preparedExerciseOption.origOption.id,
        trainingPlanXExerciseOption: preparedExerciseOption.trainingPlanXExerciseOption,
        value: value,
        defaultValue: preparedExerciseOption.defaultValue,
        isDefault: value == preparedExerciseOption.defaultValue || 0 === value.trim().length? true : false,
        name: preparedExerciseOption.origOption.name,
        origName: preparedExerciseOption.origOption.name
      });
    }
  }
}
</script>
