<template>
  <div class="flex-grid">
    <div
      v-for="exerciseOption in prepareExerciseOptions"
      :id="exerciseOption.key"
      v-bind:key="exerciseOption.key"
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
          v-bind:key="currentSelectedExercise.id+'_'+option.key"
          :active="option.isActive"
          @click="saveSelection(option, exerciseOption)"
        >
          {{ option.value }}
        </b-dropdown-item>
      </b-dropdown>
      <div v-else>
        <span>{{ exerciseOption.name }}</span>:
        <input
          v-model="exerciseOption.exerciseXExerciseOption.exerciseOptionValue"
          class="form-control"
          :placeholder="exerciseOption.placeholder"
          type="text"
        >
      </div>
    </div>
  </div>
</template>

<script>
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
    selectedOptions: {
      type: Array,
      default: () => { return new Array(); }
    }
  },
  data() {
    return {
      currentExerciseOptions: this.existingExerciseOptions,
      currentPossibleExerciseOptions: this.possibleExerciseOptions,
      currentSelectedExercise: this.selectedExercise,
      currentSelectedOptions: this.selectedOptions
    };
  },
  computed: {
    prepareExerciseOptions () {
      this.prepareSelectedOptions();
      return this.createCurrentExerciseOptions();
    }
  },
  methods: {
    createCurrentExerciseOptions() {
      let exerciseOptions = [];

      this.currentPossibleExerciseOptions.forEach( (possibleExerciseOption, index) => {
        let value = possibleExerciseOption.defaultValue;
        let name = possibleExerciseOption.name;
        let isMultipartOption = this.checkIsMultipartOption(value);
        let outerKey = this.currentSelectedExercise.id+'_'+possibleExerciseOption.id;
        let optionInformation = {
          key: outerKey,
          name: name,
          isMultipartOption: isMultipartOption,
          bindKey: outerKey+'_'+value
        };

        if (isMultipartOption) {
          optionInformation.parts = this.splitExerciseOption(value);
          optionInformation.parts.forEach( (value, index) => {
            let key = this.generateExerciseOptionPartKey(possibleExerciseOption, index);
            let partId = possibleExerciseOption.id;
            let exerciseXExerciseOption = null;

            if((undefined !== this.currentSelectedOptions[partId])) {
              exerciseXExerciseOption = this.currentSelectedOptions[partId];
            } else {
              exerciseXExerciseOption = this.generateEmptyExerciseXExerciseOption(possibleExerciseOption);
              this.currentSelectedExercise.exerciseXExerciseOptions.push(exerciseXExerciseOption);
              this.currentSelectedOptions[partId] = exerciseXExerciseOption;
            }
            let isActive = (undefined !== this.currentSelectedOptions[partId]) && this.currentSelectedOptions[partId].exerciseOptionValue == value;

            if (isActive) {
              optionInformation['name'] = optionInformation['name'] + " ("+value+")";
            }

            optionInformation.parts[index] = {
              isActive: isActive,
              value: value,
              key: key,
              exerciseXExerciseOption: exerciseXExerciseOption
            }
          });
        } else {
          optionInformation.value = (undefined !== this.currentSelectedOptions[possibleExerciseOption.id]) ? this.currentSelectedOptions[possibleExerciseOption.id].exerciseOptionValue : null;
          optionInformation.placeholder = possibleExerciseOption.defaultValue;

          let exerciseXExerciseOption = null;

          if(undefined !== this.currentSelectedOptions[possibleExerciseOption.id]) {
            exerciseXExerciseOption = this.currentSelectedOptions[possibleExerciseOption.id];
          } else {
            exerciseXExerciseOption = this.generateEmptyExerciseXExerciseOption(possibleExerciseOption);
            this.currentSelectedExercise.exerciseXExerciseOptions.push(exerciseXExerciseOption);
          }

          optionInformation.exerciseXExerciseOption = exerciseXExerciseOption;
        }

        exerciseOptions.push(optionInformation);
      });
      return exerciseOptions
    },
    prepareSelectedOptions() {
      this.currentSelectedOptions = {};

      this.currentExerciseOptions.forEach(currentExerciseOption => {
        this.currentSelectedOptions[currentExerciseOption.exerciseOption.id] = currentExerciseOption;
      });
    },
    splitExerciseOption(value) {
      return value.split('|');
    },
    saveSelection(option, preparedExerciseOption) {
      preparedExerciseOption.parts.forEach( part => {
        part.isActive = false;
        option.exerciseXExerciseOption.exerciseOptionValue = null;
      });

      option.exerciseXExerciseOption.exerciseOptionValue = option.value;
      this.currentSelectedOptions[option.exerciseXExerciseOption.exerciseOption.id] = option.exerciseXExerciseOption;
      option.isActive = true;
      preparedExerciseOption.bindKey = option.key+'_'+option.value;
    },
    checkExerciseOptionIsSelected(exerciseOption, index, value) {
      let key = this.generateExerciseOptionPartKey(exerciseOption, index);
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
    generateExerciseOptionPartKey(exerciseOption, index) {
      return this.currentSelectedExercise.id+'_'+exerciseOption.id+'_'+index;
    },
    generateEmptyExerciseXExerciseOption(exerciseOption) {
      return {
        created: null,
        creator: null,
        exercise: this.selectedExercise.id,
        exerciseOption: exerciseOption,
        exerciseOptionValue: null,
        id: null,
        updated: null,
        updater: null
      }
    }
  }
}
</script>