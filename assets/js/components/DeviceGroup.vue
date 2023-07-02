<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ origDeviceGroup.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ deviceGroup.seoLink }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DeviceGroupView",
  alias: "device-group",
  props: {
    deviceGroup: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      origDeviceGroup: this.deviceGroup
    }
  }
};
</script>
