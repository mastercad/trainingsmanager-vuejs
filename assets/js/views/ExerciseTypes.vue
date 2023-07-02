<template>
  <div>
    <div class="row mt-2">
      <h1>Exercise Types</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editExerciseType(generateNewExerciseType())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item
            v-for="currentExerciseType in exerciseTypes"
            :key="currentExerciseType.id"
            href="#"
            class="list-group-item"
            :active="exerciseType && exerciseType.id === currentExerciseType.id"
            unselectable="on"
            onselectstart="return false;"
            @contextmenu.prevent.stop="handleClick($event, currentExerciseType)"
            @click="loadExerciseType($event, currentExerciseType)"
          >
            {{ currentExerciseType.name }}
          </b-list-group-item>
        </b-list-group>
      </div>
      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="exerciseType"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="exerciseType.id"

          :key="exerciseType.id"
          :ref="'exerciseTypePanel'"

          :exercise-type="exerciseType"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :id="'ExerciseTypesContextMenu'"
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import ExerciseType from "../components/ExerciseType.vue";
import ExerciseTypeEdit from "../components/ExerciseTypeEdit.vue";
import IdGenerator from "../shared/idGenerator";

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
    },
    findOldRegistered() {
      return this.$store.getters["exerciseTypes/findOldRegistered"];
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
    async deleteExerciseType(exerciseType) {
      if (this.isGenericId(exerciseType.id)) {
        await this.$store.dispatch("exerciseTypes/unregister", exerciseType.id);
        this.leftPanelVisibility = false;
      } else {
        await this.$store.dispatch("exerciseTypes/delete", exerciseType.id);
        this.leftPanelVisibility = false;
      }
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
        this.deleteExerciseType(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('exercise_type_')
    },
    generateNewExerciseType() {
      let exerciseType = this.$store.getters["exerciseTypes/findOldRegistered"];
      if (!exerciseType) {
        exerciseType = {
          id: this.generateIdentifier(),
          name: ''
        };

        this.$store.dispatch("exerciseTypes/register", exerciseType);
      }

      return exerciseType;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('exercise_type_'));
    }
  }
};
</script>
