<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ origName }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ origDefaultValue }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExerciseOption",
  alias: "exercise-option",
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    defaultValue: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origDefaultValue: this.defaultValue
    }
  }
};
</script>
