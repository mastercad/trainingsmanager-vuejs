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
          v-bind:src="'/images/content/dynamic/devices/'+origId+'/'+origPreviewPicturePath"
        >
      </div>
    </div>
    <div
      v-if="deviceImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in deviceImages"
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
    seoLink: {
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
      origSeoLink: this.seoLink,
      origPreviewPicturePath: this.previewPicturePath
    }
  },
  computed: {
    deviceImages() {
      return this.$store.getters["devices/deviceImages"];
    },
    isImagesLoading() {
      return this.$store.getters["devices/isImagesLoading"];
    }
  },
  created() {
    this.$store.dispatch("devices/loadImages", this.id);
  },
  methods: {
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    }
  }
};
</script>
