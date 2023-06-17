<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
      <b-form-group
        label-cols-lg="3"
        label="Training Plan Layout"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this training plan layout"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'training_plan_layout_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="training_plan_layout_name"
              v-model="trainingPlanLayout.name"
              type="text"
              placeholder="Training Plan Layout Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(trainingPlanLayout.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          v-if="isValidId(trainingPlanLayout.id)"
          :disabled="trainingPlanLayout.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateTrainingPlanLayout()"
        >
          Update
        </button>
      </b-card>

      <b-card v-if="isGenericId(trainingPlanLayout.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="trainingPlanLayout.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createTrainingPlanLayout()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteTrainingPlanLayout()"
        >
          Delete
        </button>
      </b-card>
    </b-card-group>

  </b-container>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  props: {
    trainingPlanLayout: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
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
    async createTrainingPlanLayout() {
      const result = await this.$store.dispatch("trainingPlanLayouts/create",
        {
          id: this.trainingPlanLayout.id,
          name: this.trainingPlanLayout.name
        }
      );
    },
    async updateTrainingPlanLayout() {
      const result = await this.$store.dispatch(
        "trainingPlanLayouts/update",
        {
          id: this.trainingPlanLayout.id,
          name: this.trainingPlanLayout.name
        }
      );
    },
    async deleteTrainingPlanLayout() {
      const result = await this.$store.dispatch("trainingPlanLayouts/delete", this.trainingPlanLayout.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('training_plan_layout_'));
    }
  }
};
</script>
