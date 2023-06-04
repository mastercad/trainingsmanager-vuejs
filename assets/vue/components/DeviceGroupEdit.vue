<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Device Group Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origSeoLink"
          type="text"
          placeholder="Seo Link"
          class="form-control"
        >
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateDeviceGroup()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createDeviceGroup()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDeviceGroup()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    seoLink: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      currentStatus: null,
      origId: this.id,
      origName: this.name,
      origSeoLink: this.seoLink
    }
  },
  computed: {
    isInitial() {
      return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    }
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createDeviceGroup() {
      const result = await this.$store.dispatch("deviceGroups/create",
        {
          name: this.origName,
          seoLink: this.origSeoLink
        });
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
      }
    },
    async updateDeviceGroup() {
      const result = await this.$store.dispatch(
        "deviceGroups/update",
        {
          id: this.id,
          name: this.origName,
          seoLink: this.origSeoLink
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
      }
    },
    async deleteDeviceGroup() {
      const result = await this.$store.dispatch("deviceGroups/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
      }
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    }
  }
};
</script>
