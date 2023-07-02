<template>
  <div>
    <div class="row mt-2">
      <h1>Muscles</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editMuscle(generateNewMuscle())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item
            v-for="currentMuscle in muscles"
            :key="currentMuscle.id"
            href="#"
            class="list-group-item"
            :active="muscle && muscle.id === currentMuscle.id"
            unselectable="on"
            onselectstart="return false;"
            @contextmenu.prevent.stop="handleClick($event, currentMuscle)"
            @click.prevent.stop="loadMuscle($event, currentMuscle)"
          >
            {{ currentMuscle.name }}
          </b-list-group-item>
        </b-list-group>
      </div>

      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="muscle"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="muscle.id"

          :key="muscle.id"
          :ref="'musclePanel'"

          :muscle="muscle"
        />
      </div>
    </div>

    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->
    <vue-simple-context-menu
      :id="'musclesContextMenu'"
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import Muscle from "../components/Muscle.vue";
import MuscleEdit from "../components/MuscleEdit.vue";
import IdGenerator from "../shared/idGenerator";

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
    },
    findOldRegistered() {
      return this.$store.getters["muscles/findOldRegistered"];
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
    async deleteMuscle(muscle) {
      if (this.isGenericId(muscle.id)) {
        await this.$store.dispatch("muscles/unregister", muscle.id);
        this.leftPanelVisibility = false;
      } else {
        await this.$store.dispatch("muscles/delete", muscle.id);
        this.leftPanelVisibility = false;
      }
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
        this.deleteMuscle(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('muscle_')
    },
    generateNewMuscle() {
      let muscle = this.$store.getters["muscles/findOldRegistered"];

      if (!muscle) {
        muscle = {
          id: this.generateIdentifier(),
          name: '',
          seoLink: '',
          muscleXMuscleGroups: []
        };

        this.$store.dispatch("muscles/register", muscle);
      }

      return muscle;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('muscle_'));
    }
  }
};
</script>
