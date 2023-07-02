<template>
  <b-container fluid>
    <b-card
      bg-variant="light"
      class="shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="3"
        label="Device Option"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this device option"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'device_option_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_option_name"
              v-model="origDeviceOption.name"
              type="text"
              placeholder="Device Option Name"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'device_option_default_value'">Default Value:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_option_default_value"
              v-model="origDeviceOption.defaultValue"
              type="text"
              placeholder="Device default value"
              class="form-control"
              required
            />
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card-group
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-if="isValidId(origDeviceOption.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          v-if="isValidId(origDeviceOption.id)"
          :disabled="origDeviceOption.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateDeviceOption()"
        >
          Update
        </button>
      </b-card>

      <b-card
        v-if="isGenericId(origDeviceOption.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          :disabled="origDeviceOption.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createDeviceOption()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDeviceOption()"
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
  name: "DeviceOptionEditView",
  props: {
    deviceOption: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentStatus: null,
      origDeviceOption: this.deviceOption
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
    async createDeviceOption() {
      await this.$store.dispatch("deviceOptions/create",
        {
          id: this.origDeviceOption.id,
          name: this.origDeviceOption.name,
          defaultValue: this.origDeviceOption.defaultValue
        }
      );
    },
    async updateDeviceOption() {
      await this.$store.dispatch(
        "deviceOptions/update",
        {
          id: this.origDeviceOption.id,
          name: this.origDeviceOption.name,
          defaultValue: this.origDeviceOption.defaultValue
        }
      );
    },
    async deleteDeviceOption() {
      await this.$store.dispatch("deviceOptions/delete", this.origDeviceOption.id);
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device_option_'));
    }
  }
};
</script>
