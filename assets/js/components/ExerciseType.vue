<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1
          id="exercise_type_name"
        >
          {{ exerciseType.name }}
        </h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExerciseTypeView",
  alias: "exercise-type",
  props: {
    exerciseType: {
      type: Object,
      required: true
    }
  }
};
</script>
