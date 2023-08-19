<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1
          id="exercise_option_name"
        >
          {{ exerciseOption.name }}
        </h1>
      </div>
    </div>
    <div class="row">
      <div
        id="exercise_option_default_value"
        class="col-md-4"
      >
        {{ exerciseOption.defaultValue }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExerciseOptionView",
  alias: "exercise-option",
  props: {
    exerciseOption: {
      type: Object,
      required: true
    }
  }
};
</script>
