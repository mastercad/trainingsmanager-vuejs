<template>
  <div>
    <div class="row p-2 border rounded">
      TrainingPlan Layout Normal
      <h1>{{ origName }} ({{ origOrder }})</h1>
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
      origChildren: this.children
    }
  }
};
</script>
