<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ exercise.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <img
          style="width: 100%"
          v-bind:src="'/images/content/dynamic/exercises/'+exercise.id+'/'+exercise.previewPicturePath"
          @error="imageAlternative"
        >
      </div>
      <div class="col-md-8">
        {{ exercise.description }}
      </div>
    </div>
    <div class="row p-2">
      <div class="col-md-12">
        {{ exercise.specialFeatures }}
      </div>
    </div>
    <div
      v-if="exerciseImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in exerciseImages"
        v-if="isImagesLoading === false && extractFileName(image) !== exercise.previewPicturePath"
        :key="key"
        class="col-3"
      >
        <img
          class="img-thumbnail"
          :src="image"
        >
      </div>
    </div>
    <img
      v-if="isImagesLoading"
      src="/images/content/static/spinner.gif"
    >
  </div>
</template>

<script>
export default {
  props: {
    exercise: {
      required: true,
      type: Object
    }
  },
  computed: {
    exerciseImages() {
      return this.$store.getters["exercises/exerciseImages"];
    },
    isImagesLoading() {
      return this.$store.getters["exercises/isImagesLoading"];
    }
  },
  created() {
    this.$store.dispatch("exercises/loadImages", this.exercise.id);
  },
  methods: {
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    },
    imageAlternative(event) {
      event.target.src="/images/content/static/default_image.jpg";
    }
  }
};
</script>
