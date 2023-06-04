<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ origName }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ origSeoLink }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MuscleView",
  alias: "muscle",
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
    },
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origSeoLink: this.seoLink
    }
  }
};
</script>
