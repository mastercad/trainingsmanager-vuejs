<template>
  <div>
    <div class="row">
      <h1>Muscles</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editMuscle({
            id: null,
            name: '',
            seoLink: ''
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
              v-for="currentMuscle in muscles"
              :key="currentMuscle.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentMuscle)"
              @click="loadMuscle($event, currentMuscle)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: muscle && muscle.id === currentMuscle.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentMuscle.name }}
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
          v-if="muscle"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="muscle.id"

          :key="muscle.id"
          :ref="'musclePanel'"

          :name="muscle.name"
          :seo-link="muscle.seoLink"
          :selected-muscle-groups="muscle.muscleXMuscleGroups"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'musclesContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import Muscle from "../components/Muscle.vue";
import MuscleEdit from "../components/MuscleEdit.vue";

export default {
  name: "MusclesView",
  components: {
    Muscle,
    MuscleEdit
  },
  data() {
    return {
      muscle: null,
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
      return this.$store.getters["muscles/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["muscles/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["muscles/hasError"];
    },
    error() {
      return this.$store.getters["muscles/error"];
    },
    hasMuscle() {
      return this.$store.getters["muscles/hasMuscle"];
    },
    muscles() {
      return this.$store.getters["muscles/muscles"];
    }
  },
  created() {
    Promise.all([
      this.$store.dispatch("muscles/findAll"),
      this.$store.dispatch("muscleGroups/findAll")
    ]).finally(() => {
      this.loading = false
    })
  },
  methods: {
    async loadMuscle(event, muscle) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showMuscle(muscle);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editMuscle(muscle);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showMuscle(muscle) {
      this.muscle = muscle;
      this.currentPanel = 'Muscle';
      this.leftPanelVisibility=true;
    },
    async editMuscle(muscle) {
      this.muscle = muscle;
      this.currentPanel = 'MuscleEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, muscle) {
      this.$refs.vueSimpleContextMenu.showMenu(event, muscle)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showMuscle(event.item);
      } else if ('edit' === event.option.slug) {
        this.editMuscle(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteMuscle();
      }
    }
  }
};
</script>
