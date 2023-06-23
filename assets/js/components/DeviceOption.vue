<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ deviceOption.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ deviceOption.defaultValue }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DeviceOption",
  alias: "device-option",
  props: {
    deviceOption: {
      type: Object,
      required: true
    }
  }
};
</script>
