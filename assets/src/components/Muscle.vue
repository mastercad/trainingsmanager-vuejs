<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ muscle.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        {{ muscle.seoLink }}
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
  }
};
</script>
