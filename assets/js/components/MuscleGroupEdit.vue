<template>
  <b-container fluid>
    <b-card
      bg-variant="light"
      class="shadow p-2 mb-3 bg-white rounded"
    >
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
              v-model="origMuscleGroup.name"
              type="text"
              placeholder="Muscle Group Name"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'muscle_group_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="muscle_group_seo_link"
              v-model="origMuscleGroup.seoLink"
              type="text"
              placeholder="Muscle Group Seo Link"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'muscle_group_seo_link'">Color:</label>
          </b-col>
          <b-col sm="9">
            <verte
              v-model="origMuscleGroup.color"
              :picker="square"
              model="hex"
              value="'origMuscleGroup.color'"
              :style="'display: block; backgroundColor:'+origMuscleGroup.color"
            >
              <span />
            </verte>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-if="isValidId(origMuscleGroup.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          :disabled="origMuscleGroup.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateMuscleGroup()"
        >
          Update
        </button>
      </b-card>

      <b-card
        v-if="isGenericId(origMuscleGroup.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          :disabled="origMuscleGroup.name.length === 0"
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
  name: 'MUscleGroupEditView',
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
      currentStatus: null,
      origMuscleGroup: this.origMuscleGroup
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
      await this.$store.dispatch("muscleGroups/create",
        {
          id: this.origMuscleGroup.id,
          name: this.origMuscleGroup.name,
          seoLink: this.origMuscleGroup.seoLink,
          color: this.origMuscleGroup.color
        });
    },
    async updateMuscleGroup() {
      await this.$store.dispatch(
        "muscleGroups/update",
        {
          id: this.origMuscleGroup.id,
          name: this.origMuscleGroup.name,
          seoLink: this.origMuscleGroup.seoLink,
          color: this.origMuscleGroup.color
        }
      );
    },
    async deleteMuscleGroup() {
      await this.$store.dispatch("muscleGroups/delete", this.id);
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
