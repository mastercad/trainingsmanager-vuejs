<template id="drag-drop">
  <div>
    <div class="row">
      <h1>{{ origName }}</h1>

      <div
        id="sort"
        class="list-group sort cf"
      >
        <!--
          the dynamic key solves the problem that the components are not rerendered after moving,
          only id does not refresh the components, only order refresh only on first reorder if the order not 0, 1, 2 ...
        -->
        <training-plan
          v-for="(child, index) in sortTrainingPlans"
          :id="index"
          :key="child.order+'_'+child.id"

          :ref="child"

          class="list-group-item drag-el training-plan-sort-item"

          :name="child.name"
          :order="child.order"
          :user="child.user"
          :parent="child.parent"
          :training-plan-exercises="child.trainingPlanExercises"
        />
      </div>
    </div>
  </div>
</template>

<script>
import TrainingPlan from './TrainingPlan.vue';
import Sortable from 'sortablejs';

export default {
  name: "TrainingPlanSplitView",
  components: {
    TrainingPlan
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
      orderBy: 'order',
      origId: this.id,
      origName: this.name,
      origTrainingPlanLayout: this.trainingPlanLayout,
      origOwner: this.user,
      origParent: this.parent,
      origOrder: this.order,
      origChildren: this.children,
      origTrainingPlans: this.trainingPlans,
      origTrainingPlanExercises: this.trainingPlanExercises
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
  mounted() {
    var me = this;
    Sortable.create(document.getElementById('sort'), {
      draggable: '.training-plan-sort-item',
      ghostClass: "sort-ghost",
      animation: 80,
      onUpdate: function(event) {
        me.reorderTrainingPlans(event.oldIndex, event.newIndex);
      }
    });
    Sortable.create(document.getElementById('sort'), {
      draggable: '.exercise-sort-item',
      ghostClass: "sort-ghost",
      animation: 80,
      onUpdate: function(event) {
        me.reorderExercises(event.oldIndex, event.newIndex);
      }
    });
  },
  methods: {
    reorderTrainingPlans(oldIndex, newIndex) {
      console.log("reorderTrainingPlans");
      this.origChildren.splice(newIndex, 0, this.origChildren.splice(oldIndex, 1)[0]);
      this.origChildren.forEach(function(item, index) {
        item.order = index;
      });
    },
    reorderExercises(oldIndex, newIndex) {
      console.log("reorderExercises");
      this.origTrainingPlanExercises.splice(newIndex, 0, this.origTrainingPlanExercises.splice(oldIndex, 1)[0]);
      this.origTrainingPlanExercises.forEach(function(item, index) {
        item.order = index;
      });
    }
  },
  template: '#drag_drop'
};
</script>

<style scoped>
.buttons {
  margin-top: 35px;
}
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
ul.sort {
  list-style: none;
  padding: 0;
  margin: 30px;
}

li.sort-item {
  padding: 10px;
  width: 25%;
  float: left;
  margin: 0 10px 10px 0;
  background: #EFEFEF;
  border: solid 1px #CCC;
}

.sort-ghost {
  opacity: 0.3;
  transition: all 0.7s ease-out;
}
</style>