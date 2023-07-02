<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ origTrainingPlanLayout.name }}</h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TrainingPlanLayout",
  alias: "training-plan-layout",
  props: {
    trainingPlanLayout: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      origTrainingPlanLayout: this.trainingPlanLayout
    }
  }
};
</script>
