<template>
  <div>
    <div class="row">
      <h1>MuscleGroups</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editMuscleGroup({
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
              v-for="currentMuscleGroup in muscleGroups"
              :key="currentMuscleGroup.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentMuscleGroup)"
              @click="loadMuscleGroup($event, currentMuscleGroup)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: muscleGroup && muscleGroup.id === currentMuscleGroup.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentMuscleGroup.name }}
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
          v-if="muscleGroup"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="muscleGroup.id"

          :key="muscleGroup.id"
          :ref="'muscleGroupPanel'"

          :name="muscleGroup.name"
          :seo-link="muscleGroup.seoLink"
          :muscle-group-color="muscleGroup.color"
        />
      </div>
    </div>

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'muscleGroupsContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import MuscleGroup from "../components/MuscleGroup.vue";
import MuscleGroupEdit from "../components/MuscleGroupEdit.vue";

export default {
  name: "MuscleGroupsView",
  components: {
    MuscleGroup,
    MuscleGroupEdit
  },
  data() {
    return {
      muscleGroup: null,
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
      return this.$store.getters["muscleGroups/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["muscleGroups/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["muscleGroups/hasError"];
    },
    error() {
      return this.$store.getters["muscleGroups/error"];
    },
    hasMuscleGroup() {
      return this.$store.getters["muscleGroups/hasMuscleGroup"];
    },
    muscleGroups() {
      return this.$store.getters["muscleGroups/muscleGroups"];
    }
  },
  created() {
    this.$store.dispatch("muscleGroups/findAll");
  },
  methods: {
    async loadMuscleGroup(event, muscleGroup) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showMuscleGroup(muscleGroup);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editMuscleGroup(muscleGroup);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showMuscleGroup(muscleGroup) {
      this.muscleGroup = muscleGroup;
      this.currentPanel = 'MuscleGroup';
      this.leftPanelVisibility=true;
    },
    async editMuscleGroup(muscleGroup) {
      this.muscleGroup = muscleGroup;
      this.currentPanel = 'MuscleGroupEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, muscleGroup) {
      this.$refs.vueSimpleContextMenu.showMenu(event, muscleGroup)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showMuscleGroup(event.item);
      } else if ('edit' === event.option.slug) {
        this.editMuscleGroup(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteMuscleGroup();
      }
    }
  }
};
</script>
