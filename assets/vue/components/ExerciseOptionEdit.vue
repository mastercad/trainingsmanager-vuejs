<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Exercise Option Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origDefaultValue"
          type="text"
          placeholder="Default Value"
          class="form-control"
        >
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0 || origDefaultValue.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExerciseOption()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0 || origDefaultValue.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExerciseOption()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExerciseOption()"
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
      origName: this.name,
      origDefaultValue: this.defaultValue
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
          name: this.origName,
          defaultValue: this.origDefaultValue
        });
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.defaultValue = "";
      }
    },
    async updateExerciseOption() {
      const result = await this.$store.dispatch(
        "exerciseOptions/update",
        {
          id: this.id,
          name: this.origName,
          defaultValue: this.origDefaultValue
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.defaultValue = "";
      }
    },
    async deleteExerciseOption() {
      const result = await this.$store.dispatch("exerciseOptions/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.defaultValue = "";
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    }
  }
};
</script>
