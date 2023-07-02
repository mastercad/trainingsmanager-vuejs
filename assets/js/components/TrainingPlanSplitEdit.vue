<template>
  <div>
    <div class="row">
      TrainingPlan Layout Split Edit
    </div>
  </div>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  name: 'TrainingPlanSplitEditView',
  props: {
    trainingPlan: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      origTrainingPlan: this.trainingPlan,
      currentStatus: null
    }
  },
  computed: {
    isInitial() {
      return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    }
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createTrainingPlan() {
      await this.$store.dispatch("trainingPlans/create",
        {
          id: this.origTrainingPlan.id,
          name: this.origTrainingPlan.name
        }
      );
    },
    async updateTrainingPlan() {
      await this.$store.dispatch(
        "trainingPlans/update",
        {
          id: this.origTrainingPlan.id,
          name: this.origTrainingPlan.name
        }
      );
    },
    async deleteTrainingPlan() {
      await this.$store.dispatch("trainingPlans/delete", this.origTrainingPlan.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('training_plan_'));
    }
  }
};
</script>
