<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
      <b-form-group
        label-cols-lg="3"
        label="Device Group"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this device group"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'device_group_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_group_name"
              v-model="deviceGroup.name"
              type="text"
              placeholder="Device Group Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'device_group_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_group_seo_link"
              v-model="deviceGroup.seoLink"
              type="text"
              placeholder="Device Group Seo Link"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(deviceGroup.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          v-if="isValidId(deviceGroup.id)"
          :disabled="deviceGroup.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateDeviceGroup()"
        >
          Update
        </button>
      </b-card>

      <b-card v-if="isGenericId(deviceGroup.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="deviceGroup.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createDeviceGroup()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDeviceGroup()"
        >
          Delete
        </button>
      </b-card>
    </b-card-group>

  </b-container>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  props: {
    deviceGroup: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentStatus: null
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
          id: this.deviceGroup.id,
          name: this.deviceGroup.name,
          seoLink: this.deviceGroup.seoLink
        });
    },
    async updateDeviceGroup() {
      const result = await this.$store.dispatch(
        "deviceGroups/update",
        {
          id: this.deviceGroup.id,
          name: this.deviceGroup.name,
          seoLink: this.deviceGroup.seoLink
        }
      );
    },
    async deleteDeviceGroup() {
      const result = await this.$store.dispatch("deviceGroups/delete", this.deviceGroup.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device_group_'));
    }
  }
};
</script>
