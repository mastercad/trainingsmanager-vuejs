<template>
  <div>
    <div class="row">
      <h1>Exercise Types</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editExerciseType({
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
              v-for="currentExerciseType in exerciseTypes"
              :key="currentExerciseType.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentExerciseType)"
              @click="loadExerciseType($event, currentExerciseType)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: exerciseType && exerciseType.id === currentExerciseType.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentExerciseType.name }}
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
          v-if="exerciseType"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exerciseType.id"

          :key="exerciseType.id"
          :ref="'exerciseTypePanel'"

          :name="exerciseType.name"
          :default-value="exerciseType.defaultValue"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'ExerciseTypesContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import ExerciseType from "../components/ExerciseType.vue";
import ExerciseTypeEdit from "../components/ExerciseTypeEdit.vue";

export default {
  name: "ExerciseTypesView",
  components: {
    ExerciseType,
    ExerciseTypeEdit
  },
  data() {
    return {
      exerciseType: null,
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
      return this.$store.getters["exerciseTypes/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["exerciseTypes/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["exerciseTypes/hasError"];
    },
    error() {
      return this.$store.getters["exerciseTypes/error"];
    },
    hasExerciseType() {
      return this.$store.getters["exerciseTypes/hasExerciseType"];
    },
    exerciseTypes() {
      return this.$store.getters["exerciseTypes/exerciseTypes"];
    }
  },
  created() {
    this.$store.dispatch("exerciseTypes/findAll");
  },
  methods: {
    async loadExerciseType(event, exerciseType) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showExerciseType(exerciseType);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editExerciseType(exerciseType);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showExerciseType(exerciseType) {
      this.exerciseType = exerciseType;
      this.currentPanel = 'ExerciseType';
      this.leftPanelVisibility=true;
    },
    async editExerciseType(exerciseType) {
      this.exerciseType = exerciseType;
      this.currentPanel = 'ExerciseTypeEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, exerciseType) {
      this.$refs.vueSimpleContextMenu.showMenu(event, exerciseType)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showExerciseType(event.item);
      } else if ('edit' === event.option.slug) {
        this.editExerciseType(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteExerciseType();
      }
    }
  }
};
</script>
