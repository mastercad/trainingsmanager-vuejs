<template>
  <div class="card w-100 mt-2">
    <div class="card-body">
      {{ origFirstName }}
      {{ origLastName }}
      {{ origEmailAddress }}
    </div>
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origFirstName"
          type="text"
          placeholder="First Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origLastName"
          type="text"
          placeholder="Last Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origEmailAddress"
          type="text"
          placeholder="Email Address"
          class="form-control"
        >
      </div>
    </div>
    <div class="row">
      <div class="col-3">
        <button
          :disabled="origFirstName.length === 0 || origLastName.length === 0 || origEmailAddress.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateContact()"
        >
          Update
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteContact()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ContactView",
  props: {
    id: {
      type: Number,
      default: -99999
    },
    firstName: {
      type: String,
      required: true
    },
    lastName: {
      type: String,
      required: true
    },
    emailAddress: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      origFirstName: this.firstName,
      origLastName: this.lastName,
      origEmailAddress: this.emailAddress
    }
  },
  methods: {
    async updateContact() {
      const result = await this.$store.dispatch(
        "contacts/update",
        {
          id: this.id,
          firstName: this.origFirstName,
          lastName: this.origLastName,
          emailAddress: this.origEmailAddress
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.firstName = "";
        this.$data.lastName = "";
        this.$data.emailAddress = "";
      }
    },
    async deleteContact() {
      const result = await this.$store.dispatch("contacts/delete", this.id);
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
