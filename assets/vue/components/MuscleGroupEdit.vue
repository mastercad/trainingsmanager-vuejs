<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="MuscleGroup Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origSeoLink"
          type="text"
          placeholder="Seo Link"
          class="form-control"
        >
      </div>
    </div>

    <div class="row" :style="{ backgroundColor: color }">
      <verte
        picker="square"
        model="hex"
        value="'color'"
        v-model="color"
      >
        <button class="btn btn-primary">
          Color Picker
        </button>
      </verte>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateMuscleGroup()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createMuscleGroup()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteMuscleGroup()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

import Verte from 'verte';

import 'verte/dist/verte.css';

export default {
  components: {
    Verte
  },
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    seoLink: {
      type: String,
      required: true
    },
    muscleGroupColor: {
      type: String,
      required: true,
      default: '#FFFFFF'
    }
  },
  data() {
    return {
      currentStatus: null,
      origId: this.id,
      origName: this.name,
      origSeoLink: this.seoLink,
      color: this.muscleGroupColor
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
    async createMuscleGroup() {
      const result = await this.$store.dispatch("muscleGroups/create",
        {
          name: this.origName,
          seoLink: this.origSeoLink,
          color: this.color
        });
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.muscleGroupColor = "";
      }
    },
    async updateMuscleGroup() {
      const result = await this.$store.dispatch(
        "muscleGroups/update",
        {
          id: this.id,
          name: this.origName,
          seoLink: this.origSeoLink,
          color: this.color
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.muscleGroupColor = "";
      }
    },
    async deleteMuscleGroup() {
      const result = await this.$store.dispatch("muscleGroups/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.muscleGroupColor = "";
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    }
  }
};
</script>
