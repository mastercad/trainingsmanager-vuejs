<template>
  <div>
    <div class="row col">
      <h1>Register</h1>
    </div>

    <div class="row col">
      <form>
        <div class="form-row">
          <div class="col-4">
            <input
              v-model="email"
              type="email"
              class="form-control"
              placeholder="email"
            >
          </div>
          <div class="col-4">
            <input
              v-model="login"
              type="text"
              class="form-control"
              placeholder="login"
            >
          </div>
          <div class="col-4">
            <input
              v-model="firstName"
              type="text"
              class="form-control"
              placeholder="First Name"
            >
          </div>
          <div class="col-4">
            <input
              v-model="lastName"
              type="text"
              class="form-control"
              placeholder="Name"
            >
          </div>
          <div class="col-4">
            <input
              v-model="firstPassword"
              type="password"
              class="form-control"
              placeholder="Password"
            >
          </div>
          <div class="col-4">
            <input
              v-model="secondPassword"
              type="password"
              class="form-control"
              placeholder="Repeat Password"
            >
          </div>
          <div class="col-4">
            <button
              :disabled="email.length === 0 || firstName.length === 0 || lastName.length === 0 || firstPassword.length === 0 || secondPassword.length === 0 || firstPassword !== secondPassword || isLoading"
              type="button"
              class="btn btn-primary"
              @click="performRegister()"
            >
              Register
            </button>
          </div>
        </div>
      </form>
    </div>

    <div
      v-if="isLoading"
      class="row col"
    >
      <p>Loading...</p>
    </div>

    <div
      v-else-if="hasError"
      class="row col"
    >
      <div
        class="alert alert-danger"
        role="alert"
      >
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "RegisterView",
  data() {
    return {
      email: "",
      login: "",
      firstName: "",
      lastName: "",
      firstPassword: "",
      secondPassword: ""
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["security/isLoading"];
    },
    hasError() {
      return this.$store.getters["security/hasError"];
    },
    error() {
      return this.$store.getters["security/error"];
    }
  },
  created() {
    let redirect = this.$route.query.redirect;

    if (true === this.$store.getters["security/isAuthenticated"]) {
      if (typeof redirect !== "undefined") {
        this.$router.push({path: redirect});
      } else {
        this.$router.push({path: "/home"});
      }
    }

  },
  methods: {
    async performRegister() {
      let payload = {email: this.$data.email, login: this.$data.login, firstName: this.$data.firstName, lastName: this.$data.lastName, firstPassword: this.$data.firstPassword, secondPassword: this.$data.secondPassword},
        redirect = this.$route.query.redirect;

      await this.$store.dispatch("security/register", payload);

      if (!this.$store.getters["security/hasError"]) {
        if (typeof redirect !== "undefined") {
          this.$router.push({path: redirect});
        } else {
          this.$router.push({path: "/home"});
        }
      }

    }
  }
}
</script>