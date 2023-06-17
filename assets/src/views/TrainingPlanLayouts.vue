<template>
  <div>
    <div class="row mt-2">
      <h1>TrainingPlan Layouts</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editTrainingPlanLayout(generateNewTrainingPlanLayout())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item href="#"
            v-for="currentTrainingPlanLayout in trainingPlanLayouts"
            :key="currentTrainingPlanLayout.id"
            @contextmenu.prevent.stop="handleClick($event, currentTrainingPlanLayout)"
            @click="loadTrainingPlanLayout($event, currentTrainingPlanLayout)"
            class="list-group-item"
            :active="trainingPlanLayout && trainingPlanLayout.id === currentTrainingPlanLayout.id"
            unselectable="on"
            onselectstart="return false;"
          >
            {{ currentTrainingPlanLayout.name }}
          </b-list-group-item>
        </b-list-group>
      </div>
      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="trainingPlanLayout"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="trainingPlanLayout.id"

          :key="trainingPlanLayout.id"
          :ref="'trainingPlanLayoutPanel'"

          :trainingPlanLayout="trainingPlanLayout"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'TrainingPlanLayoutsContextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import TrainingPlanLayout from "../components/TrainingPlanLayout.vue";
import TrainingPlanLayoutEdit from "../components/TrainingPlanLayoutEdit.vue";
import IdGenerator from "../shared/idGenerator";

export default {
  name: "TrainingPlanLayoutsView",
  components: {
    TrainingPlanLayout,
    TrainingPlanLayoutEdit
  },
  data() {
    return {
      trainingPlanLayout: null,
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
      return this.$store.getters["trainingPlanLayouts/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["trainingPlanLayouts/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["trainingPlanLayouts/hasError"];
    },
    error() {
      return this.$store.getters["trainingPlanLayouts/error"];
    },
    hasTrainingPlanLayout() {
      return this.$store.getters["trainingPlanLayouts/hasTrainingPlanLayout"];
    },
    trainingPlanLayouts() {
      return this.$store.getters["trainingPlanLayouts/trainingPlanLayouts"];
    },
    findOldRegistered() {
      return this.$store.getters["trainingPlanLayouts/findOldRegistered"];
    }
  },
  created() {
    this.$store.dispatch("trainingPlanLayouts/findAll");
  },
  methods: {
    async loadTrainingPlanLayout(event, trainingPlanLayout) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showTrainingPlanLayout(trainingPlanLayout);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editTrainingPlanLayout(trainingPlanLayout);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showTrainingPlanLayout(trainingPlanLayout) {
      this.trainingPlanLayout = trainingPlanLayout;
      this.currentPanel = 'TrainingPlanLayout';
      this.leftPanelVisibility=true;
    },
    async editTrainingPlanLayout(trainingPlanLayout) {
      this.trainingPlanLayout = trainingPlanLayout;
      this.currentPanel = 'TrainingPlanLayoutEdit';
      this.leftPanelVisibility = true;
    },
    async deleteTrainingPlanLayout(trainingPlanLayout) {
      if (this.isGenericId(trainingPlanLayout.id)) {
        const result = await this.$store.dispatch("trainingPlanLayouts/unregister", trainingPlanLayout.id);
        this.leftPanelVisibility = false;
      } else {
        const result = await this.$store.dispatch("trainingPlanLayouts/delete", trainingPlanLayout.id);
        this.leftPanelVisibility = false;
      }
    },
    async handleClick(event, trainingPlanLayout) {
      this.$refs.vueSimpleContextMenu.showMenu(event, trainingPlanLayout)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showTrainingPlanLayout(event.item);
      } else if ('edit' === event.option.slug) {
        this.editTrainingPlanLayout(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteTrainingPlanLayout(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('training_plan_layout_')
    },
    generateNewTrainingPlanLayout() {
      let trainingPlanLayout = this.$store.getters["trainingPlanLayouts/findOldRegistered"];
      if (!trainingPlanLayout) {
        trainingPlanLayout = {
          id: this.generateIdentifier(),
          name: ''
        };

        this.$store.dispatch("trainingPlanLayouts/register", trainingPlanLayout);
      }

      return trainingPlanLayout;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('training_plan_layout_'));
    }
  }
};
</script>
