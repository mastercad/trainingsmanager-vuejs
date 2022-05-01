<template>
  <div class="flex-grid">
    <div
      v-for="exerciseOption in prepareOptions"
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
          v-model="currentSelectedTrainingPlanExerciseOptions[option.exerciseOption.id]"
          class="form-control"
          :placeholder="exerciseOption.placeholder"
          type="text"
        >
      </div>
    </div>
  </div>
</template>

<script>
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
    selectedTrainingPlanExerciseOptions: {
      type: Object,
      default: () => { return {}; }
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
      origCurrentTrainingPlanExerciseOptions: this.currentTrainingPlanExerciseOptions,
      currentSelectedTrainingPlanExerciseOptions: this.selectedTrainingPlanExerciseOptions,
    };
  },
  computed: {
    prepareOptions () {
      let extractIdClosure = function(option) {
        return option.exerciseOption.id;
      };
      let extractValueClosure = function(option) {
        return option.optionValue;
      };
      return OptionFunctions.generateCurrentOptions(
        this.currentSelectedExercise.id,
        this.currentPossibleExerciseOptions,
        this.currentSelectedTrainingPlanExerciseOptions,
        this.origCurrentTrainingPlanExerciseOptions
      );
    }
  },
  methods: {
    saveSelection(option, preparedExerciseOption) {
      preparedExerciseOption.parts.forEach( part => {
        part.isActive = false;
        option.exerciseXExerciseOption.exerciseOptionValue = null;
      });

      option.exerciseXExerciseOption.exerciseOptionValue = option.value;
      this.currentSelectedTrainingPlanExerciseOptions[option.exerciseXExerciseOption.exerciseOption.id] = option.exerciseXExerciseOption;
      option.isActive = true;
      preparedExerciseOption.bindKey = option.key+'_'+option.value;
    }
  }
}
</script>
