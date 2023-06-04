<template>
  <div>
    <div class="row">
      <h1>Exercise Options</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editExerciseOption({
            id: null,
            name: '',
            defaultValue: ''
          })"
        >
          Add
        </button>
      </div>
    </div>
    <div class="row gx-0">
      <div
        class="col-3"
        style="background-color: lime;"
      >
        <div style="padding-right: 15px; position: absolute; overflow-y: auto; height: 100%; max-height: 100%;">
          <ul>
            <li
              v-for="currentExerciseOption in exerciseOptions"
              :key="currentExerciseOption.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentExerciseOption)"
              @click="loadExerciseOption($event, currentExerciseOption)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: exerciseOption && exerciseOption.id === currentExerciseOption.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentExerciseOption.name }}
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div
        class="col-9"
        style="background-color: lightblue;"
      >
        <component
          :is="currentPanel"
          v-if="exerciseOption"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exerciseOption.id"

          :key="exerciseOption.id"
          :ref="'exerciseOptionPanel'"

          :name="exerciseOption.name"
          :default-value="exerciseOption.defaultValue"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'ExerciseOptionsContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import ExerciseOption from "../components/ExerciseOption.vue";
import ExerciseOptionEdit from "../components/ExerciseOptionEdit.vue";

export default {
  name: "ExerciseOptionsView",
  components: {
    ExerciseOption,
    ExerciseOptionEdit
  },
  data() {
    return {
      exerciseOption: null,
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
      return this.$store.getters["exerciseOptions/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["exerciseOptions/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["exerciseOptions/hasError"];
    },
    error() {
      return this.$store.getters["exerciseOptions/error"];
    },
    hasExerciseOption() {
      return this.$store.getters["exerciseOptions/hasExerciseOption"];
    },
    exerciseOptions() {
      return this.$store.getters["exerciseOptions/exerciseOptions"];
    }
  },
  created() {
    this.$store.dispatch("exerciseOptions/findAll");
  },
  methods: {
    async loadExerciseOption(event, exerciseOption) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showExerciseOption(exerciseOption);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editExerciseOption(exerciseOption);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showExerciseOption(exerciseOption) {
      this.exerciseOption = exerciseOption;
      this.currentPanel = 'ExerciseOption';
      this.leftPanelVisibility=true;
    },
    async editExerciseOption(exerciseOption) {
      this.exerciseOption = exerciseOption;
      this.currentPanel = 'ExerciseOptionEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, exerciseOption) {
      this.$refs.vueSimpleContextMenu.showMenu(event, exerciseOption)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showExerciseOption(event.item);
      } else if ('edit' === event.option.slug) {
        this.editExerciseOption(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteExerciseOption();
      }
    }
  }
};
</script>
