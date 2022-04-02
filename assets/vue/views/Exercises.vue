<template>
  <div>
    <div class="row">
      <h1>Exercises</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editExercise({
            id: null,
            name: '',
            description: '',
            seoLink: '',
            specialFeatures: '',
            previewPicturePath: ''
          })"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-4">
        <div style="padding-right: 15px; position: absolute; overflow-y: auto; height: 100%; max-height: 100%;">
          <ul>
            <li
              v-for="currentExercise in exercises"
              :key="currentExercise.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentExercise)"
              @click="loadExercise($event, currentExercise)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: exercise && exercise.id === currentExercise.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentExercise.name }}
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-8">
        <component
          :is="currentPanel"
          v-if="exercise"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exercise.id"

          :key="exercise.id"
          :ref="'exercisePanel'"

          :name="exercise.name"
          :seo-link="exercise.seoLink"
          :description="exercise.description"
          :special-features="exercise.specialFeatures"
          :preview-picture-path="exercise.previewPicturePath"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import Exercise from "../components/Exercise";
import ExerciseEdit from "../components/ExerciseEdit";

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
    }
  },
  created() {
    this.$store.dispatch("exercises/findAll");
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
        this.deleteExercise();
      }
    }
  }
};
</script>

<style>
  .col.list-group-item {
    cursor: pointer;
  }
</style>
