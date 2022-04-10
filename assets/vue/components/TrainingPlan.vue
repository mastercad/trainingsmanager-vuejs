<template>
  <div>
    <div class="row p-2 border rounded">
      <h1>{{ origName }}</h1>
      <div
        v-if="origTrainingPlanExercises"
      >
        <div
          v-for="trainingPlanExercise in origTrainingPlanExercises"
          :key="trainingPlanExercise.exercise.name"
          class="exercise-sort-item"
        >
          {{ trainingPlanExercise.exercise.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TrainingPlanView",
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    parent: {
      type: String,
      default: null
    },
    order: {
      type: Number,
      default: 1
    },
    user: {
      type: String,
      required: true
    },
    children: {
      type: Array,
      default: () => []
    },
    trainingPlanExercises: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origTrainingPlanLayout: this.trainingPlanLayout,
      origOwner: this.user,
      origParent: this.parent,
      origOrder: this.order,
      origChildren: this.children,
      origTrainingPlanExercises: this.trainingPlanExercises
    }
  }
};
</script>
