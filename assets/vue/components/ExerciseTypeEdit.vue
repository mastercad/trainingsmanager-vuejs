<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Exercise Type Name"
          class="form-control"
        >
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExerciseType()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExerciseType()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExerciseType()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    defaultValue: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      currentStatus: null,
      origId: this.id,
      origName: this.name
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
          name: this.origName
        });
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
      }
    },
    async updateExerciseType() {
      const result = await this.$store.dispatch(
        "exerciseTypes/update",
        {
          id: this.id,
          name: this.origName
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
      }
    },
    async deleteExerciseType() {
      const result = await this.$store.dispatch("exerciseTypes/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    }
  }
};
</script>
