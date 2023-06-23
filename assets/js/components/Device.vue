<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1 id="device_name">{{ device.name }}</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <img
          style="width: 100%"
          v-bind:src="'/images/content/dynamic/devices/'+device.id+'/'+device.previewPicturePath"
          @error="imageAlternative"
        >
      </div>
    </div>

    <div class="flex-grid">
      <div
        v-for="deviceOption in prepareDeviceOptions"
        :id="deviceOption.key"
        v-bind:key="deviceOption.key"
      >
        <b-dropdown
          v-if="deviceOption.isMultipartOption"
          split-variant="outline-primary"
          variant="primary"
          class="m-md-2"
          :text="deviceOption.name"
        >
          <b-dropdown-item
            v-for="option in deviceOption.parts"
            :id="option.key"
            v-bind:key="option.key"
            :active="option.isActive"
          >
            {{ option.value }}
          </b-dropdown-item>
        </b-dropdown>
      </div>
    </div>

    <div
      v-if="deviceImages && 0 < deviceImages.length"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in deviceImages"
        v-if="isImagesLoading === false && extractFileName(image) !== device.previewPicturePath"
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

import OptionFunctions from "../shared/optionFunctions.js";

export default {
  props: {
    device: {
      type: Object,
      required: true
    },
    possibleDeviceOptions: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentPossibleDeviceOptions: this.possibleDeviceOptions
    }
  },
  computed: {
    prepareDeviceOptions () {
      return OptionFunctions.generateCurrentOptions(
        undefined !== this.device ? this.device.id : null,
        this.currentPossibleDeviceOptions
      );
    },
    deviceImages() {
      return this.$store.getters["devices/deviceImages"];
    },
    isImagesLoading() {
      return this.$store.getters["devices/isImagesLoading"];
    }
  },
  created() {
    this.$store.dispatch("devices/loadImages", this.device.id);
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
