<template>
  <div>
    <div class="row col">
      <h1>Exercises</h1>
    </div>

    <div class="row col">
      <div class="col-4">
        <ul>
          <li
            v-for="exercise in exercises"
            :key="exercise.id"
            class="row"
            @contextmenu.prevent.stop="handleClick($event, exercise)"
            @click="showExercise(exercise.id)"
          >
            <div class="col list-group-item list-group-item-action">
              {{ exercise.name }}
            </div>
          </li>
        </ul>
      </div>
      <div class="col-8">
        <component
          :is="currentPanel"
          v-if="exercise"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exercise.id"
          :name="exercise.name"
          :seo-link="exercise.seoLink"
          :description="exercise.description"
          :special-features="exercise.specialFeatures"
          :preview-picture-path="exercise.previewPicturePath"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability
    to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'myFirstMenu'"
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
    console.log("EXERCISES VUE CREATED!");
    this.$store.dispatch("exercises/findAll");
  },
  methods: {
    async loadExercise(event, id) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showExercise(id);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editExercise(id);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async createExercise() {
      const result = await this.$store.dispatch("exercises/create", this.$data);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.description = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
      }
    },
/*
    async showExercise(id) {
      this.leftPanelVisibility = false;
      const result = await this.$store.dispatch("exercises/findExercise", id);
      if (result !== null) {
        this.leftPanelVisibility=true;
        this.currentPanel = 'Exercise';
      }
    },
*/
    async showExercise(exercise) {
      console.log("SHOW!");
      console.log(exercise.name);
      this.leftPanelVisibility=false;
      this.exercise = exercise;
      this.currentPanel = 'Exercise';
      this.leftPanelVisibility=true;
    },
/*
    async editExercise(id) {
      this.leftPanelVisibility = false;
      const result = await this.$store.dispatch("exercises/findExercise", id);
      if (result !== null) {
        this.leftPanelVisibility = true;
        this.currentPanel = 'ExerciseEdit';
      }
    },
*/
    async editExercise(exercise) {
      console.log("EDIT!");
      console.log(exercise.name);
      this.leftPanelVisibility=false;
      this.exercise = exercise;
      this.currentPanel = 'ExerciseEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, exercise) {
      this.$refs.vueSimpleContextMenu.showMenu(event, exercise)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        console.log("SHOW!");
        console.log(event.item.name);
        this.showExercise(event.item);
      } else if ('edit' === event.option.slug) {
        console.log("EDIT!");
        console.log(event.item.name);
        this.editExercise(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteExercise();
      }
//        window.alert(JSON.stringify(event));
    }
  }
};
</script>
