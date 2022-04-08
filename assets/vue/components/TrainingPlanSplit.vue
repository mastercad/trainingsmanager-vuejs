<template>
  <div>
    <div class="row">
      TrainingPlan Layout Split
      <h1>{{ origName }}</h1>

      <div
        class="drop-zone"

        outlined

        @drop="onDrop($event)"
      >
        <draggable
          :list="origChildren"
          group="trainingPlans"
          class="list-group"
          ghost-class="ghost"
          :move="onMove"
          @start="drag=true"
          @end="drag=false"
        >
          <training-plan
            v-for="(child, index) in origChildren"
            :id="index"
            :key="child.order"

            :ref="child"

            class="list-group-item drag-el"

            :name="child.name"
            :order="child.order"
            :user="child.user"
            :parent="child.parent"
          />
        </draggable>
      </div>
    </div>
  </div>
</template>

<script>
import TrainingPlan from './TrainingPlan.vue';
import draggable from 'vuedraggable'

export default {
  name: "TrainingPlanSplitView",
  components: {
    TrainingPlan,
    draggable
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
    }
  },
  ready() {
    var vm = this;
    Sortable.create(document.getElementById('sort'), {
      draggable: 'li.sort-item',
      ghostClass: "sort-ghost",
      animation: 80,
      onUpdate: function(evt) {
        console.log('dropped (Sortable)');
        vm.reorder(evt.oldIndex, evt.newIndex);
      }
    });
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
      origTrainingPlans: null,
      futureIndex: 0,
      origIndex: 0
    }
  },
  computed: {
    sortTrainingPlans() {
      console.log("SORT TRAININGPLANS");
      /*
      if (null === this.origTrainingPlans) {
        this.origTrainingPlans = this.origChildren.sort(
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
      return this.origTrainingPlans;
      */

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
    onDrop(event) {
      console.log("ON DROP!");
      console.log(event);

      this.arrayMove(this.origChildren, this.origIndex, this.futureIndex);

      let newOrder = 0;
      this.origChildren.forEach(trainingPlanItem => {
        console.log(trainingPlanItem);
        console.log(trainingPlanItem.name);
        console.log(trainingPlanItem.order);
        trainingPlanItem.order = newOrder;
        ++newOrder;
      });
    },
    onMove: function(e) {
      this.futureIndex = e.draggedContext.futureIndex;
      this.origIndex = e.draggedContext.index;
    },
    arrayMove: function(arr, oldIndex, newIndex) {
      if (newIndex >= arr.length) {
        var k = newIndex - arr.length + 1;
        while (k--) {
          arr.push(undefined);
        }
      }
      arr.splice(newIndex, 0, arr.splice(oldIndex, 1)[0]);
      return arr;
    }
  },
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
</style>