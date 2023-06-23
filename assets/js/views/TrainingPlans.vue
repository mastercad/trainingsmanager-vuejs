<template>
  <div>
    <div class="row mt-2">
      <h1>Training Plans</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editTrainingPlan(generateNewTrainingPlan())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-4">
        <ul>
          <li
            v-for="currentTrainingPlan in trainingPlans"
            :key="currentTrainingPlan.id"
            class="row"
            @contextmenu.prevent.stop="handleClick($event, currentTrainingPlan)"
            @click="loadTrainingPlan($event, currentTrainingPlan)"
          >
            <div
              class="list-group-item list-group-item-action"
              :class="{ active: trainingPlan && trainingPlan.id === currentTrainingPlan.id }"
              unselectable="on"
              onselectstart="return false;"
            >
              {{ currentTrainingPlan.name }}
            </div>
          </li>
        </ul>
      </div>
      <div class="col-8">
        <b-dropdown
          split-variant="outline-primary"
          variant="primary"
          class="m-md-2"
          :text="trainingPlanLayoutText"
        >
          <b-dropdown-item
            v-for="currentTrainingPlanLayout in trainingPlanLayouts"
            :id="currentTrainingPlanLayout.id"
            :key="currentTrainingPlanLayout.name"
            :active="trainingPlanLayoutText === currentTrainingPlanLayout.name"
            @click="switchTrainingPlanLayout(currentTrainingPlanLayout)"
          >
            {{ currentTrainingPlanLayout.name }}
          </b-dropdown-item>
        </b-dropdown>
        <component
          :is="currentPanel"
          v-if="trainingPlan"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="trainingPlan.id"

          :key="trainingPlan.id"
          :ref="'trainingPlanPanel'"

          :trainingPlan="trainingPlan"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'trainingPlansContextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import TrainingPlan from "../components/TrainingPlan";
import TrainingPlanSplit from "../components/TrainingPlanSplit";
import TrainingPlanEdit from  "../components/TrainingPlanEdit";
import TrainingPlanSplitEdit from  "../components/TrainingPlanSplitEdit";
import IdGenerator from "../shared/idGenerator";

export default {
  name: "TrainingPlansView",
  components: {
    TrainingPlan,
    TrainingPlanSplit,
    TrainingPlanEdit,
    TrainingPlanSplitEdit
  },
  data() {
    return {
      trainingPlan: null,
      trainingPlanLayout: null,
      trainingPlanLayoutText: 'Training Plan Layout',
      panelMode: null,
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
      return this.$store.getters["trainingPlans/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["trainingPlans/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["trainingPlans/hasError"];
    },
    error() {
      return this.$store.getters["trainingPlans/error"];
    },
    hasTrainingPlans() {
      return this.$store.getters["trainingPlans/hasTrainingPlans"];
    },
    trainingPlans() {
      return this.$store.getters["trainingPlans/trainingPlans"].filter(
        trainingPlan => trainingPlan.parent === null
      );
    },
    hasTrainingPlanLayouts() {
      return this.$store.getters["trainingPlanLayouts/hasTrainingPlans"];
    },
    trainingPlanLayouts() {
      return this.$store.getters["trainingPlanLayouts/trainingPlanLayouts"];
    },
    possibleExercises() {
      return this.$store.getters["exercises/exercises"];
    },
    possibleDevices() {
      return this.$store.getters["devices/devices"];
    },
    possibleExerciseOptions() {
      return this.$store.getters["exerciseOptions/exerciseOptions"];
    },
    possibleDeviceOptions() {
      return this.$store.getters["deviceOptions/deviceOptions"];
    },
    findOldRegistered() {
      return this.$store.getters["trainingPlans/findOldRegistered"];
    }
  },
  created() {
    this.$store.dispatch("trainingPlans/findAll");
    this.$store.dispatch("trainingPlanLayouts/findAll");
    this.$store.dispatch("exercises/findAll");
    this.$store.dispatch("devices/findAll");
    this.$store.dispatch("exerciseOptions/findAll");
    this.$store.dispatch("deviceOptions/findAll");
  },
  methods: {
    async loadTrainingPlan(event, trainingPlan) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showTrainingPlan(trainingPlan);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editTrainingPlan(trainingPlan);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showTrainingPlan(trainingPlan) {
      this.panelMode = 'show';
      this.trainingPlan = trainingPlan;
      this.trainingPlanLayoutText = trainingPlan.trainingPlanLayout.name;
      this.currentPanel = this.trainingPlanLayoutText === 'Split' ? 'TrainingPlanSplit' : 'TrainingPlan';
      this.leftPanelVisibility=true;
    },
    async editTrainingPlan(trainingPlan) {
      this.panelMode = 'edit';
      this.trainingPlan = trainingPlan;
      this.trainingPlanLayoutText = trainingPlan.trainingPlanLayout.name;
      this.currentPanel = this.trainingPlanLayoutText === 'Split' ? 'TrainingPlanSplitEdit' : 'TrainingPlanEdit';
      this.leftPanelVisibility = true;
    },
    async deleteMuscle(trainingPlan) {
      if (this.isGenericId(trainingPlan.id)) {
        const result = await this.$store.dispatch("trainingPlans/unregister", trainingPlan.id);
        this.leftPanelVisibility = false;
      } else {
        const result = await this.$store.dispatch("trainingPlans/delete", trainingPlan.id);
        this.leftPanelVisibility = false;
      }
    },
    async handleClick(event, trainingPlan) {
      this.$refs.vueSimpleContextMenu.showMenu(event, trainingPlan)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showTrainingPlan(event.item);
      } else if ('edit' === event.option.slug) {
        this.editTrainingPlan(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteTrainingPlan(event.item);
      }
    },
    switchTrainingPlanLayout(trainingPlanLayout) {
      this.trainingPlanLayoutText = trainingPlanLayout.name;
      if ('show' === this.panelMode) {
        this.currentPanel = 'Split' === this.trainingPlanLayoutText ? 'TrainingPlanSplit' : 'TrainingPlan';
      } else if ('edit' === this.panelMode) {
        this.currentPanel = 'Split' === this.trainingPlanLayoutText ? 'TrainingPlanSplitEdit' : 'TrainingPlanEdit';
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('training_plan_')
    },
    generateNewTrainingPlan() {
      let trainingPlan = this.$store.getters["trainingPlans/findOldRegistered"];

      if (!trainingPlan) {
        trainingPlan = {
          id: this.generateIdentifier(),
          name: '',
          active: false,
          order: 0,
          user: {},
          parent: null,
          children: [],
          trainingPlanLayout: {},
          trainingPlanXExercises: []
        };

        this.$store.dispatch("trainingPlans/register", trainingPlan);
      }

      return trainingPlan;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('training_plan_'));
    }
  }
};
</script>
