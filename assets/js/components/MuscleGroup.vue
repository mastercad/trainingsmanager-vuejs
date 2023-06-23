<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ muscleGroup.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ muscleGroup.seoLink }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MuscleGroupView",
  alias: "muscleGroup",
  props: {
    muscleGroup: {
      type: Object,
      required: true
    }
  }
};
</script>
