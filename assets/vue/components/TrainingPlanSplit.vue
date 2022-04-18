<template id="drag-drop">
  <div>
    <div class="row">
      <h1>{{ origName }}</h1>

      <draggable
        :list="origChildren"
        class="list-group"
        ghost-class="ghost"
        :options="{group:'tags'}"
        @change="onTrainingPlanMove"
      >
        <!--
          the dynamic key solves the problem that the components are not rerendered after moving,
          only id does not refresh the components, only order refresh only on first reorder if the order not 0, 1, 2 ...
        -->
        <training-plan
          v-for="(child, index) in sortTrainingPlans"
          :id="child.id"
          :key="child.order+'_'+child.id"
          :ref="child"

          :sort="child.order"

          class="list-group-item drag-el training-plan-sort-item"

          :name="child.name"
          :order="child.order"
          :user="child.user"
          :parent="child.parent"
          :training-plan-exercises="child.trainingPlanExercises"
        />
      </draggable>
    </div>
  </div>
</template>

<script>
import TrainingPlan from './TrainingPlan.vue';

import draggable from 'vuedraggable';

export default {
  name: "TrainingPlanSplitView",
  components: {
    TrainingPlan,
    draggable: draggable,
  },
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    parent: {
      type: String,
      default: null
    },
    order: {
      type: Number,
      default: 1
    },
    user: {
      type: String,
      required: true
    },
    children: {
      type: Array,
      default: () => []
    },
    trainingPlanExercises: {
      type: Array(),
      default: () => []
    }
  },
  data() {
    return {
      display: 'Clone',
      orderBy: 'order',
      origId: this.id,
      origName: this.name,
      origTrainingPlanLayout: this.trainingPlanLayout,
      origOwner: this.user,
      origParent: this.parent,
      origOrder: this.order,
      origChildren: this.children,
      origTrainingPlans: this.trainingPlans,
      origTrainingPlanExercises: this.trainingPlanExercises,
      newIndex: 0,
      exerciseMoveTarget: null
    }
  },
  computed: {
    sortTrainingPlans() {
     return this.origChildren.sort(
        (a, b) => { // sort using this.orderBy
          const first = a[this.orderBy]
          const next = b[this.orderBy]
          if (first > next) {
            return 1
          }
          if (first < next) {
            return -1
          }
          return 0
        }
      );
    }
  },
  methods: {
    onTrainingPlanMove(event) {
      console.log("reorderTrainingPlans");
      window.console.log(event.moved);
      this.origChildren.splice(event.moved.newIndex, 0, this.origChildren.splice(event.moved.oldIndex, 1)[0]);
      this.origChildren.forEach(function(item, index) {
        item.order = index;
      });
    }
  },
  template: '#drag_drop'
};
</script>

<style scoped>
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
  transition: all 0.7s ease-out;
}
</style>
