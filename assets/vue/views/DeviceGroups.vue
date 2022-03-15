<template>
  <div class="row gx-0">
    <div
      class="col-3"
      style="background-color: lime;"
    >
      One of three columns
    </div>
    <div
      class="col-9"
      style="background-color: lightblue;"
    >
      One of three columns
    </div>
  </div>
</template>

<script>
import DeviceGroup from "../components/DeviceGroup.vue";

export default {
  name: "DeviceGroups",
  components: {
    DeviceGroup
  },
  created() {
    this.$store.dispatch("device-groups/findAll");
  },
  methods:
  {

  }
};
</script>
