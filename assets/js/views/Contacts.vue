<template>
  <div>
    <div class="row col">
      <h1>Contacts</h1>
    </div>

    <div class="row col">
      <form>
        <div class="form-row">
          <div class="col-8">
            <input
              v-model="firstName"
              type="text"
              placeholder="First Name"
              class="form-control"
            >
          </div>
          <div class="col-8">
            <input
              v-model="lastName"
              type="text"
              placeholder="Last Name"
              class="form-control"
            >
          </div>
          <div class="col-8">
            <input
              v-model="emailAddress"
              type="text"
              placeholder="Email Address"
              class="form-control"
            >
          </div>
          <div class="col-4">
            <button
              :disabled="firstName.length === 0 || lastName.length === 0 || emailAddress.length === 0 || isLoading"
              type="button"
              class="btn btn-primary"
              @click="createContact()"
            >
              Create
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

    <div
      v-else-if="!hasContacts"
      class="row col"
    >
      Keine Kontakte!
    </div>

    <div
      v-for="contact in contacts"
      v-else
      :key="contact.id"
      class="row col"
    >
      <contact
        :id="contact.id"
        :first-name="contact.firstName"
        :last-name="contact.lastName"
        :email-address="contact.emailAddress"
      />
    </div>
  </div>
</template>

<script>
import Contact from "../components/Contact";

export default {
  name: "ContactsView",
  components: {
    Contact
  },
  data() {
    return {
      id: -9999,
      firstName: "",
      lastName: "",
      emailAddress: ""
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["contacts/isLoading"];
    },
    hasError() {
      return this.$store.getters["contacts/hasError"];
    },
    error() {
      return this.$store.getters["contacts/error"];
    },
    hasContacts() {
      return this.$store.getters["contacts/hasContacts"];
    },
    contacts() {
      return this.$store.getters["contacts/contacts"];
    }
  },
  created() {
    this.$store.dispatch("contacts/findAll");
  },
  methods: {
    async createContact() {
      const result = await this.$store.dispatch("contacts/create", this.$data);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.firstName = "";
        this.$data.lastName = "";
        this.$data.emailAddress = "";
      }
    }
  }
};
</script>
