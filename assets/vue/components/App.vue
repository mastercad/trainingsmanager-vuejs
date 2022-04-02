<template>
  <b-container style="height: calc(100vh - 120px); position: relative; top: 0; bottom: 0;">
    <b-navbar
      toggleable="lg"
      type="dark"
      variant="info"
    >
      <b-navbar-brand href="#">
        NavBar
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse" />

      <b-collapse
        id="nav-collapse"
        is-nav
      >
        <b-navbar-nav>
          <b-nav-item
            v-for="item in $router.options.routes"
            v-if="!item.children"
            :key="item.name"
            :to="item.path"
          >
            {{ item.name }}
          </b-nav-item>
          <b-nav-item-dropdown
            v-for="item in $router.options.routes"
            v-if="item.children"
            :key="item.name"
            :text="item.name"
            active-class="active"
          >
            <b-dropdown-item
              v-for="child in item.children"
              :key="child.name"
              :to="child.path"
              active-class="active"
            >
              {{ child.name }}
            </b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-form>
            <b-form-input
              size="sm"
              class="mr-sm-2"
              placeholder="Search"
            />
            <b-button
              size="sm"
              class="my-2 my-sm-0"
              type="submit"
            >
              Search
            </b-button>
          </b-nav-form>

          <b-nav-item-dropdown
            text="Lang"
            right
          >
            <b-dropdown-item href="#">
              EN
            </b-dropdown-item>
            <b-dropdown-item href="#">
              DE
            </b-dropdown-item>
            <b-dropdown-item href="#">
              RU
            </b-dropdown-item>
          </b-nav-item-dropdown>

          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em>User</em>
            </template>
            <b-dropdown-item href="#">
              Profile
            </b-dropdown-item>
            <b-dropdown-item href="#">
              Sign Out
            </b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>

    <router-view />
<!--
    <li
      v-if="isAuthenticated==true"
      class="nav-item"
    >
      <a
        class="nav-link"
        href="/logout"
      >Logout</a>
    </li>
-->
    <!--
    <button class="btn">
      Notification
      <span class="badge badge-primary" />
    </button>
    <b-alert
      v-bind:show="showAlert"
      variant="success"
    >
      You clicked the button!
    </b-alert>
    <b-btn
      v-b-tooltip.hover
      title="This button triggers the alert"
      variant="primary"
      @click="showAlert = true"
    >
      Click
    </b-btn>

    <div>
      <h2 class="center">
        My Application
      </h2>
      <div v-text="message" />
      <ul>
        <li
          v-for="word in words"
          :key="word.id"
        >
          {{ word }}
        </li>
      </ul>
      <h2>another test</h2>
      <span class="counter">
        {{ counter }}
      </span>
      <button @click="incrementCounter">
        +
      </button>
    </div>
-->
  </b-container>
</template>

<script>
import store from "../store";
import router from "../router";

export default {
  name: 'App',
  data() {
    return {
      message: "A list of words",
      words: [],
      counter: 0,
      showAlert: false
    };
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters['security/isAuthenticated']
    }
  },
  mounted() {
    /*
    let el = document.querySelector("div[data-words]");
    let myWords = el.dataset.words.split(",");

    this.words.push.apply(this.words, myWords);
    */
  },
  created() {
    let payload = {
      isAuthenticated: localStorage.getItem('isAuthenticated'),
      user: localStorage.getItem('user'),
      token: localStorage.getItem('token'),
      refreshToken: localStorage.getItem('refreshToken')
    };
    this.$store.dispatch("security/onReload", payload);

//    this.$router.push({ name: 'testRoute', path: '/exercises/new' });

    router.addRoutes(
      [
        {
          name: 'Logout1',
          path: '/users/logout'
        }
      ]
    );

    router.addRoutes(
      [
        {
          name: 'Logout2',
          path: '/users/logout'
        }
      ]
    );

//    router.beforeEach((to, from, next) => {
//      if (to.name !== 'Login' && !this.isAuthenticated) next({ name: 'Login' })
//      else next()
//    })

    //router.replace(router.currentRoute.value.fullPath)

    console.log(router);
/*
    axios.interceptors.response.use(undefined, (err) => {
      return new Promise(() => {
        if (401 === err.response.status) {
          this.$router.push({path: "/login"})
        } else if (500 === err.response.status) {
          document.open();
          document.write(err.response.data);
          document.close();
        }
        throw err;
      });
    });
*/
  },
  methods: {
    incrementCounter() {
      this.counter += 1;
    },
    logout() {
      store.dispatch('security/clearUserData');
    }
  },
};
</script>

<style>
.center {
  text-align: center;
}
.counter {
  color: #f00;
}

</style>
