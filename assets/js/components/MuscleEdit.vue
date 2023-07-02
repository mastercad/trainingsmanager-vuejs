<template>
  <b-container fluid>
    <b-card
      bg-variant="light"
      class="shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="3"
        label="Muscle"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this muscle"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'muscle_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="muscle_name"
              v-model="origMuscle.name"
              type="text"
              placeholder="Muscle Name"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'muscle_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="muscle_seo_link"
              v-model="origMuscle.seoLink"
              type="text"
              placeholder="Muscle Seo Link"
              class="form-control"
              required
            />
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
      title="Muscle Group"
    >
      <b-card-group deck>
        <b-card no-body>
          <b-dropdown
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="'add muscle group'"
          >
            <b-dropdown-item
              v-for="possibleMuscleGroup in muscleGroups"
              :id="'muscle_group_dropdown_'+possibleMuscleGroup.id"
              :key="'muscle_group_dropdown_'+possibleMuscleGroup.id"
              :active="checkMuscleGroupSelected(possibleMuscleGroup)" 
              @click="saveMuscleGroupSelection(possibleMuscleGroup)"
            >
              {{ possibleMuscleGroup.name }}
            </b-dropdown-item>
          </b-dropdown>
        </b-card>

        <b-card>
          <div class="flex-grid">
            <div
              v-for="currentSelectedMuscleGroup in origMuscle.muscleXMuscleGroups"
              :id="'muscle_group_'+currentSelectedMuscleGroup.muscleGroup.id"
              :key="'muscle_group_'+currentSelectedMuscleGroup.muscleGroup.id"
            >
              <div>
                <span>{{ currentSelectedMuscleGroup.muscleGroup.name }}</span>
              </div>
            </div>
          </div>
        </b-card>
      </b-card-group>
    </b-card>

    <b-card-group
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-if="isValidId(origMuscle.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          v-if="isValidId(origMuscle.id)"
          :disabled="origMuscle.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateMuscle()"
        >
          Update
        </button>
      </b-card>

      <b-card
        v-if="isGenericId(origMuscle.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          :disabled="origMuscle.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createMuscle()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteMuscle()"
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
  name: 'MuscleEditView',
  props: {
    muscle: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentStatus: null,
      origMuscle: this.muscle
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
      await this.$store.dispatch("muscles/create",
        {
          id: this.origMuscle.id,
          name: this.origMuscle.name,
          seoLink: this.origMuscle.seoLink,
          muscleXMuscleGroups: this.collectSelectedMuscleGroups()
        });
    },
    async updateMuscle() {
      await this.$store.dispatch(
        "muscles/update",
        {
          id: this.origMuscle.id,
          name: this.origMuscle.name,
          seoLink: this.origMuscle.seoLink,
          muscleXMuscleGroups: this.collectSelectedMuscleGroups()
        }
      );
    },
    async deleteMuscle() {
      await this.$store.dispatch("muscles/delete", this.origMuscle.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    collectSelectedMuscleGroups() {
      let muscleXMuscleGroups = new Array();

      this.origMuscle.muscleXMuscleGroups.forEach(currentSelectedMuscleGroup => {
        let muscleXMuscleGroup = {};
        muscleXMuscleGroup['strain'] = currentSelectedMuscleGroup['strain'] ?? 0;
        muscleXMuscleGroup['muscleGroup'] = '/api/muscle_groups/'+currentSelectedMuscleGroup['muscleGroup']['id'];

        if (this.isValidId(this.origMuscle.id)) {
          muscleXMuscleGroup['muscle'] = '/api/muscles/'+this.origMuscle.id;
        }

        if (currentSelectedMuscleGroup['id']) {
          muscleXMuscleGroup['id'] = currentSelectedMuscleGroup['id'];
        }
        muscleXMuscleGroups.push(muscleXMuscleGroup);
      });

      return muscleXMuscleGroups;
    },
    saveMuscleGroupSelection(selectedMuscleGroup) {
      let index = this.origMuscle.muscleXMuscleGroups.findIndex(currentSelectedMuscleGroup => currentSelectedMuscleGroup.muscleGroup.id == selectedMuscleGroup.id);
      if (0 <= index) {
        this.origMuscle.muscleXMuscleGroups.splice(index, 1);
      } else {
        let muscleXMuscleGroup = {
          muscle: {
            id: this.origMuscle.id,
            name: this.origMuscle.name,
            seoLink: this.origMuscle.seoLink
          },
          muscleGroup: selectedMuscleGroup
        };
        this.origMuscle.muscleXMuscleGroups.push(muscleXMuscleGroup);
      }
    },
    checkMuscleGroupSelected(possibleMuscleGroup) {
      return 0 <= this.origMuscle.muscleXMuscleGroups.findIndex(currentSelectedMuscleGroup => currentSelectedMuscleGroup.muscleGroup.id == possibleMuscleGroup.id);
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('muscle_'));
    }
  }
};
</script>
