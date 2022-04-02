<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ origName }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <img
          style="width: 100%"
          v-bind:src="'/images/content/dynamic/exercises/'+origId+'/'+origPreviewPicturePath"
        >
      </div>
      <div class="col-md-8">
        {{ origDescription }}
      </div>
    </div>
    <div class="row p-2">
      <div class="col-md-12">
        {{ origSpecialFeatures }}
      </div>
    </div>
    <div
      v-if="exerciseImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in exerciseImages"
        v-if="isImagesLoading === false && extractFileName(image) !== origPreviewPicturePath"
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
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    },
    seoLink: {
      type: String,
      required: true
    },
    specialFeatures: {
      type: String,
      required: true
    },
    previewPicturePath: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origDescription: this.description,
      origSeoLink: this.seoLink,
      origSpecialFeatures: this.specialFeatures,
      origPreviewPicturePath: this.previewPicturePath
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
    this.$store.dispatch("exercises/loadImages", this.id);
  },
  methods: {
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    }
  }
};
</script>
