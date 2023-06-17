<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
      <b-form-group
        label-cols-lg="3"
        label="Exercise Option"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this exercise option"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'exercise_option_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_option_name"
              v-model="exerciseOption.name"
              type="text"
              placeholder="Exercise Option Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_option_default_value'">Default Value:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_option_default_value"
              v-model="exerciseOption.defaultValue"
              type="text"
              placeholder="Exercise Option Default Value"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(exerciseOption.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          v-if="isValidId(exerciseOption.id)"
          :disabled="exerciseOption.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExerciseOption()"
        >
          Update
        </button>
      </b-card>

      <b-car v-if="isGenericId(exerciseOption.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="exerciseOption.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExerciseOption()"
        >
          Create
        </button>
      </b-car>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExerciseOption()"
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
    exerciseOption: {
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
    async createExerciseOption() {
      const result = await this.$store.dispatch("exerciseOptions/create",
        {
          id: this.exerciseOption.id,
          name: this.exerciseOption.name,
          defaultValue: this.exerciseOption.defaultValue
        }
      );
    },
    async updateExerciseOption() {
      const result = await this.$store.dispatch(
        "exerciseOptions/update",
        {
          id: this.exerciseOption.id,
          name: this.exerciseOption.name,
          defaultValue: this.exerciseOption.defaultValue
        }
      );
    },
    async deleteExerciseOption() {
      const result = await this.$store.dispatch("exerciseOptions/delete", this.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('exercise_option_'));
    }
  }
};
</script>
