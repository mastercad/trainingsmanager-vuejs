<template>
  <div>
    <div class="row mt-2">
      <h1>Exercises</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editExercise(generateNewExercise())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item href="#"
            v-for="currentExercise in exercises"
            :key="currentExercise.id"
            @contextmenu.prevent.stop="handleClick($event, currentExercise)"
            @click="loadExercise($event, currentExercise)"
            class="list-group-item"
            :active="exercise && exercise.id === currentExercise.id"
            unselectable="on"
            onselectstart="return false;"
          >
            {{ currentExercise.name }}
          </b-list-group-item>
        </b-list-group>
      </div>

      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="exercise"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exercise.id"

          :key="exercise.id"
          :ref="'exercisePanel'"
          :possible-device-options="deviceOptions"
          :possible-exercise-options="exerciseOptions"
          :exercise="exercise"
        />
      </div>
    </div>

    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->
    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'exercisesContextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import Exercise from "../components/Exercise";
import ExerciseEdit from "../components/ExerciseEdit";
import IdGenerator from "../shared/idGenerator";

export default {
  name: "ExercisesView",
  components: {
    Exercise,
    ExerciseEdit
  },
  data() {
    return {
      exercise: null,
      result: [],
      delay: 200,
      clicks: 0,
      timer: null,
      currentPanel: '',
      leftPanelVisibility: false,
      options: [
        {
          name: 'Edit',
          slug: 'edit'
        },
        {
          name: 'Show',
          slug: 'show'
        },
        {
          type: 'divider'
        },
        {
          name: 'Delete',
          slug: 'delete'
        }
      ]
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["exercises/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["exercises/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["exercises/hasError"];
    },
    error() {
      return this.$store.getters["exercises/error"];
    },
    hasExercises() {
      return this.$store.getters["exercises/hasExercises"];
    },
    exercises() {
      return this.$store.getters["exercises/exercises"];
    },
    exerciseOptions() {
      return this.$store.getters["exerciseOptions/exerciseOptions"];
    },
    deviceOptions() {
      return this.$store.getters["deviceOptions/deviceOptions"];
    },
    muscles() {
      return this.$store.getters["muscles/muscles"];
    },
    findOldRegistered() {
      return this.$store.getters["exercises/findOldRegistered"];
    }
  },
  created() {
    Promise.all([
      this.$store.dispatch("deviceOptions/findAll"),
      this.$store.dispatch("exerciseOptions/findAll"),
      this.$store.dispatch("exercises/findAll"),
      this.$store.dispatch("muscles/findAll")
    ]).finally(() => {
      this.loading = false
    })
  },
  methods: {
    async loadExercise(event, exercise) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showExercise(exercise);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editExercise(exercise);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showExercise(exercise) {
      this.exercise = exercise;
      this.currentPanel = 'Exercise';
      this.leftPanelVisibility=true;
    },
    async editExercise(exercise) {
      this.exercise = exercise;
      this.currentPanel = 'ExerciseEdit';
      this.leftPanelVisibility = true;
    },
    async deleteExercise(exercise) {
      if (this.isGenericId(exercise.id)) {
        const result = await this.$store.dispatch("exercises/unregister", exercise.id);
        this.leftPanelVisibility = false;
      } else {
        const result = await this.$store.dispatch("exercises/delete", exercise.id);
        this.leftPanelVisibility = false;
      }
    },
    async handleClick(event, exercise) {
      this.$refs.vueSimpleContextMenu.showMenu(event, exercise)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showExercise(event.item);
      } else if ('edit' === event.option.slug) {
        this.editExercise(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteExercise(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('exercise_')
    },
    generateNewExercise() {
      let exercise = this.$store.getters["exercises/findOldRegistered"];

      if (!exercise) {
        exercise = {
          id: this.generateIdentifier(),
          name: '',
          description: '',
          seoLink: '',
          specialFeatures: '',
          previewPicturePath: '',
          exerciseXDevices: [],
          exerciseXExerciseType: [],
          exerciseXDeviceOptions: [],
          exerciseXExerciseOptions: []
        };

        this.$store.dispatch("exercises/register", exercise);
      }

      return exercise;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device_'));
    }
  }
};
</script>

<style>
  .col.list-group-item {
    cursor: pointer;
  }
</style>
