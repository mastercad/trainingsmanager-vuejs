<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Muscle Name"
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

    <div class="row">
      <b-dropdown
        split-variant="outline-primary"
        variant="primary"
        class="m-md-2"
        :text="'select muscle group'"
      >
        <b-dropdown-item
          v-for="possibleMuscleGroup in muscleGroups"
          :id="'muscle_group_dropdown_'+possibleMuscleGroup.id"
          v-bind:key="'muscle_group_dropdown_'+possibleMuscleGroup.id"
          :active="checkMuscleGroupSelected(possibleMuscleGroup)"
          @click="saveMuscleGroupSelection(possibleMuscleGroup)"
        >
          {{ possibleMuscleGroup.name }}
        </b-dropdown-item>
      </b-dropdown>
    </div>

    <div class="row">
      <div class="flex-grid">
        <div
          v-for="currentSelectedMuscleGroup in currentSelectedMuscleGroups"
          :id="'muscle_group_'+currentSelectedMuscleGroup.muscleGroup.id"
          :key="'muscle_group_'+currentSelectedMuscleGroup.muscleGroup.id"
        >
          <div>
            <span>{{ currentSelectedMuscleGroup.muscleGroup.name }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateMuscle()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createMuscle()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteMuscle()"
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
    seoLink: {
      type: String,
      required: true
    },
    selectedMuscleGroups: {
      type: Array,
      default: () => { return new Array(); }
    }
  },
  data() {
    return {
      currentStatus: null,
      origId: this.id,
      origName: this.name,
      origSeoLink: this.seoLink,
      currentSelectedMuscleGroups: this.selectedMuscleGroups
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
    },
    muscleGroups() {
      return this.$store.getters["muscleGroups/muscleGroups"];
    }
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createMuscle() {
      const result = await this.$store.dispatch("muscles/create",
        {
          name: this.origName,
          seoLink: this.origSeoLink,
          muscleXMuscleGroups: this.collectSelectedMuscleGroups()
        });
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.selectedMuscleGroups = new Array();
      }
    },
    async updateMuscle() {
      const result = await this.$store.dispatch(
        "muscles/update",
        {
          id: this.id,
          name: this.origName,
          seoLink: this.origSeoLink,
          muscleXMuscleGroups: this.collectSelectedMuscleGroups()
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.selectedMuscleGroups = new Array();
      }
    },
    async deleteMuscle() {
      const result = await this.$store.dispatch("muscles/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.selectedMuscleGroups = new Array();
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    collectSelectedMuscleGroups() {
      let muscleXMuscleGroups = new Array();

      this.currentSelectedMuscleGroups.forEach(currentSelectedMuscleGroup => {
        let muscleXMuscleGroup = {};
        muscleXMuscleGroup['strain'] = currentSelectedMuscleGroup['strain'] ?? 0;
        muscleXMuscleGroup['muscle'] = '/api/muscles/'+this.origId;
        muscleXMuscleGroup['muscleGroup'] = '/api/muscle_groups/'+currentSelectedMuscleGroup['muscleGroup']['id'];

        if (currentSelectedMuscleGroup['id']) {
          muscleXMuscleGroup['id'] = currentSelectedMuscleGroup['id'];
        }
        muscleXMuscleGroups.push(muscleXMuscleGroup);
      });

      return muscleXMuscleGroups;
    },
    saveMuscleGroupSelection(selectedMuscleGroup) {
      let index = this.currentSelectedMuscleGroups.findIndex(currentSelectedMuscleGroup => currentSelectedMuscleGroup.muscleGroup.id == selectedMuscleGroup.id);
      if (0 <= index) {
        this.currentSelectedMuscleGroups.splice(index, 1);
      } else {
        let muscleXMuscleGroup = {
          muscle: {
            id: this.origId,
            name: this.origName,
            seoLink: this.origSeoLink
          },
          muscleGroup: selectedMuscleGroup
        };
        this.currentSelectedMuscleGroups.push(muscleXMuscleGroup);
      }
    },
    checkMuscleGroupSelected(possibleMuscleGroup) {
      return 0 <= this.currentSelectedMuscleGroups.findIndex(currentSelectedMuscleGroup => currentSelectedMuscleGroup.muscleGroup.id == possibleMuscleGroup.id);
    }
  }
};
</script>
