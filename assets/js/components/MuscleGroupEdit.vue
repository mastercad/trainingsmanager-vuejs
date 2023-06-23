<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
      <b-form-group
        label-cols-lg="3"
        label="Muscle Group"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this muscle group"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'muscle_group_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="muscle_group_name"
              v-model="muscleGroup.name"
              type="text"
              placeholder="Muscle Group Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'muscle_group_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="muscle_group_seo_link"
              v-model="muscleGroup.seoLink"
              type="text"
              placeholder="Muscle Group Seo Link"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'muscle_group_seo_link'">Color:</label>
          </b-col>
          <b-col sm="9">
            <verte
              :picker="square"
              model="hex"
              value="'muscleGroup.color'"
              v-model="muscleGroup.color"
              :style="'display: block; backgroundColor:'+muscleGroup.color"
            >
              <span></span>
            </verte>
          </b-col>
        </b-row>

      </b-form-group>
    </b-card>

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(muscleGroup.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="muscleGroup.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateMuscleGroup()"
        >
          Update
        </button>
      </b-card>

      <b-card v-if="isGenericId(muscleGroup.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="muscleGroup.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createMuscleGroup()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteMuscleGroup()"
        >
          Delete
        </button>
      </b-card>
    </b-card-group>

  </b-container>
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
    muscleGroup: {
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
    async createMuscleGroup() {
      const result = await this.$store.dispatch("muscleGroups/create",
        {
          id: this.muscleGroup.id,
          name: this.muscleGroup.name,
          seoLink: this.muscleGroup.seoLink,
          color: this.muscleGroup.color
        });
    },
    async updateMuscleGroup() {
      const result = await this.$store.dispatch(
        "muscleGroups/update",
        {
          id: this.muscleGroup.id,
          name: this.muscleGroup.name,
          seoLink: this.muscleGroup.seoLink,
          color: this.muscleGroup.color
        }
      );
    },
    async deleteMuscleGroup() {
      const result = await this.$store.dispatch("muscleGroups/delete", this.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('muscle_group_'));
    }
  }
};
</script>

<style>
  .verte__guide{
    width: 100%;
    height: 20px;
    border: 0;
  }
</style>
