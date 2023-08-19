<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1 id="device_option_name">
          {{ origDeviceOption.name }}
        </h1>
      </div>
    </div>
    <div class="row">
      <div
        id="device_option_default_value"
        class="col-md-4"
      >
        {{ origDeviceOption.defaultValue }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DeviceOptionView",
  alias: "device-option",
  props: {
    deviceOption: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      origDeviceOption: this.deviceOption
    }
  }
};
</script>
