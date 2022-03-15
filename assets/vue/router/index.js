import Vue from "vue";
import VueRouter from "vue-router";
import Admin from "../views/Admin";
import Home from "../views/Home";
import Contacts from "../views/Contacts";
import DeviceGroups from "../views/DeviceGroups.vue";
import DeviceOptions from "../views/DeviceOptions.vue";
import Devices from "../views/Devices.vue";
import Exercises from "../views/Exercises.vue";
import ExerciseOptions from "../views/ExerciseOptions.vue";
import ExerciseNew from "../views/ExerciseNew.vue";
import Permissions from "../views/Permissions.vue";
import Muscles from "../views/Muscles.vue";
import MuscleGroups from "../views/MuscleGroups.vue";
import TrainingPlans from "../views/TrainingPlans.vue";
import TrainingPlanNew from "../views/TrainingPlanNew.vue";
import Login from "../views/Login.vue";
import TrainingPlansArchive from "../views/TrainingPlansArchive.vue";

import store from "../store";

Vue.use(VueRouter);

let router = new VueRouter({
  mode: "history",
  routes: [
    { path: '/home', title: 'Home', name: 'Home', component: Home },
    {
      path: '/admin',
      name: 'Admin',
      component: Admin,
      children: [
        { path: '/admin/muscle', name: 'Muscle', component: Muscles, meta: { requiresAuth: true }  },
        { path: '/admin/muscle-groups', name: 'Muscle Groups', component: MuscleGroups, meta: { requiresAuth: true } },
        { path: '/admin/devices', name: 'Devices', component: Devices, meta: { requiresAuth: true }  },
        { path: '/admin/device-groups', name: 'Device Groups', component: DeviceGroups, meta: { requiresAuth: true }  },
        { path: '/admin/device-options', name: 'Device Options', component: DeviceOptions, meta: { requiresAuth: true }  },
        { path: '/admin/exercise-options', name: 'Exercise Options', component: ExerciseOptions, meta: { requiresAuth: true } },
        { path: '/admin/permissions', name: 'Permissions', component: Permissions, meta: { requiresAuth: true }  },
        { path: '/admin/contacts', name: 'Contacts', component: Contacts, meta: { requiresAuth: true }  },
        { path: '/authentication_token', name: 'Generate Token'},
        { path: '/api', name: 'API Documentation' }
      ]
    },
    {
      path: '/exercises',
      name: 'Exercises',
      component: Admin,
      children: [
        { path: '/exercises/index', name: 'Overview Exercises', component: Exercises, meta: { requiresAuth: true }  },
        { path: '/exercises/new', name: 'New Exercise', component: ExerciseNew, meta: { requiresAuth: true }  }
      ]
    },
    {
      path: '/training-plans',
      name: 'Training Plans',
      component: Admin,
      children: [
        { path: '/training-plans/index', name: 'Overview Training Plans', component: TrainingPlans, meta: { requiresAuth: true }  },
        { path: '/training-plans/new', name: 'New Training Plan', component: TrainingPlanNew, meta: { requiresAuth: true } },
        { path: '', name: 'divider' },
        { path: '/training-plans/archive', name: 'Training Plan Archive', component: TrainingPlansArchive, meta: { requiresAuth: true } }
      ]
    },
    {
      path: '/users',
      name: 'User',
      component: Admin,
      children: [
        { path: '/login', name: 'Login', component: Login }
      ]
    }
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    console.log(from);
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (true === store.getters["security/isAuthenticated"]) {
      next();
    } else {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;
