<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1
          id="muscle_name"
        >
          {{ origMuscle.name }}
        </h1>
      </div>
    </div>
    <div class="row">
      <div
        id="muscle_seo_link"
        class="col-md-4"
      >
        {{ origMuscle.seoLink }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MuscleView",
  alias: "muscle",
  props: {
    muscle: {
      required: true,
      type: Object
    }
  },
  data() {
    return {
      origMuscle: this.muscle
    }
  }
};
</script>
