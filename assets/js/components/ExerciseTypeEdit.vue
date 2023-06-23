<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
      <b-form-group
        label-cols-lg="3"
        label="Exercise Type"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this exercise type"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'exercise_type_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_type_name"
              v-model="exerciseType.name"
              type="text"
              placeholder="Exercise Type Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(exerciseType.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          v-if="isValidId(exerciseType.id)"
          :disabled="exerciseType.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExerciseType()"
        >
          Update
        </button>
      </b-card>

      <b-card v-if="isGenericId(exerciseType.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="exerciseType.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExerciseType()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExerciseType()"
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
    exerciseType: {
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
    async createExerciseType() {
      const result = await this.$store.dispatch("exerciseTypes/create",
        {
          id: this.exerciseType.id,
          name: this.exerciseType.name
        }
      );
    },
    async updateExerciseType() {
      const result = await this.$store.dispatch(
        "exerciseTypes/update",
        {
          id: this.exerciseType.id,
          name: this.exerciseType.name
        }
      );
    },
    async deleteExerciseType() {
      const result = await this.$store.dispatch("exerciseTypes/delete", this.exerciseType.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('exercise_type_'));
    }
  }
};
</script>
