<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1
          id="muscle_group_name"
        >
          {{ origMuscleGroup.name }}
        </h1>
      </div>
    </div>
    <div class="row">
      <div
        id="muscle_group_seo_link"
        class="col-md-4"
      >
        {{ origMuscleGroup.seoLink }}
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
  },
  data() {
    return {
      origMuscleGroup: this.muscleGroup
    };
  }
};
</script>
