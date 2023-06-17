<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ exerciseType.name }}</h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExerciseType",
  alias: "exercise-type",
  props: {
    exerciseType: {
      type: Object,
      required: true
    }
  }
};
</script>
