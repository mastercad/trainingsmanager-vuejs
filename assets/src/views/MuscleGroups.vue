<template>
  <div>
    <div class="row mt-2">
      <h1>MuscleGroups</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editMuscleGroup(generateNewMuscleGroup())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item href="#"
            v-for="currentMuscleGroup in muscleGroups"
            :key="currentMuscleGroup.id"
            @contextmenu.prevent.stop="handleClick($event, currentMuscleGroup)"
            @click="loadMuscleGroup($event, currentMuscleGroup)"
            class="list-group-item"
            :active="muscleGroup && muscleGroup.id === currentMuscleGroup.id"
            unselectable="on"
            onselectstart="return false;"
          >
            {{ currentMuscleGroup.name }}
          </b-list-group-item>
        </b-list-group>
      </div>

      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="muscleGroup"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="muscleGroup.id"

          :key="muscleGroup.id"
          :ref="'muscleGroupPanel'"

          :muscleGroup="muscleGroup"
        />
      </div>
    </div>

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'muscleGroupsContextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import MuscleGroup from "../components/MuscleGroup.vue";
import MuscleGroupEdit from "../components/MuscleGroupEdit.vue";
import IdGenerator from "../shared/idGenerator";

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
    },
    findOldRegistered() {
      return this.$store.getters["muscleGroups/findOldRegistered"];
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
    async deleteMuscleGroup(muscleGroup) {
      if (this.isGenericId(muscleGroup.id)) {
        const result = await this.$store.dispatch("muscleGroups/unregister", muscleGroup.id);
        this.leftPanelVisibility = false;
      } else {
        const result = await this.$store.dispatch("muscleGroups/delete", muscleGroup.id);
        this.leftPanelVisibility = false;
      }
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
        this.deleteMuscleGroup(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('muscle_group_')
    },
    generateNewMuscleGroup() {
      let muscleGroup = this.$store.getters["muscleGroups/findOldRegistered"];

      if (!muscleGroup) {
        muscleGroup = {
          id: this.generateIdentifier(),
          name: '',
          seoLink: '',
          color: '#FFFFFF'
        };

        this.$store.dispatch("muscleGroups/register", muscleGroup);
      }

      return muscleGroup;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('muscle_group_'));
    }
  }
};
</script>
