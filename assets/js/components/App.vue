<template>
  <b-container>
    <div>
      <b-navbar
        toggleable="lg"
        type="dark"
        variant="info"
      >
        <b-navbar-toggle target="nav-collapse" />

        <b-navbar-brand href="#">
          NavBar
        </b-navbar-brand>

        <b-collapse
          id="nav-collapse"
          is-nav
        >
          <b-navbar-nav>
            <nav-item
              v-for="item in routes"
              :key="item.name"
              :item="item"
            />
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
    </div>

    <router-view />
  </b-container>
</template>

<script>
import store from "../store";
import router from "../router";
import NavItem from "./NavItem.vue"

export default {
  name: "AppView",
  components: {
    NavItem
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters['security/isAuthenticated']
    },
    routes() {
      return router.options.routes;
    }
  },
  created() {
    let payload = {
      isAuthenticated: localStorage.getItem('isAuthenticated'),
      user: localStorage.getItem('user'),
      token: localStorage.getItem('token'),
      refreshToken: localStorage.getItem('refreshToken')
    };
    this.$store.dispatch("security/onReload", payload);

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
