<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-12">
        <h1 id="device_name">
          {{ device.name }}
        </h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <img
          alt="Device Preview Picture"
          style="width: 100%"
          :src="'/images/content/dynamic/devices/'+device.id+'/'+device.previewPicturePath"
          @error="imageAlternative"
        >
      </div>
    </div>

    <div class="flex-grid">
      <div
        v-for="deviceOption in prepareDeviceOptions"
        :id="deviceOption.key"
        :key="deviceOption.key"
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
            :key="option.key"
            :active="option.isActive"
          >
            {{ option.value }}
          </b-dropdown-item>
        </b-dropdown>
      </div>
    </div>

    <div
      v-if="cleanedDeviceImages.length"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in cleanedDeviceImages"
        :key="key"
        class="col-3"
      >
        <img
          class="img-thumbnail"
          :src="image"
          :alt="image"
        >
      </div>
    </div>
    <img
      v-if="isImagesLoading"
      alt="Content loading ..."
      src="/images/content/static/spinner.gif"
    >
  </div>
</template>

<script>

import OptionFunctions from "../shared/optionFunctions.js";

export default {
  name: "DeviceView",
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
    },
    cleanedDeviceImages() {
      if(this.isImagesLoading) {
        return [];
      }

      let cleanedDeviceImages = [];
      for(let deviceImagePosition in this.deviceImages) {
        let deviceImage = this.deviceImages[deviceImagePosition];
        if (this.extractFileName(deviceImage) !== this.device.previewPicturePath) {
          cleanedDeviceImages.push(deviceImage);
        }
      }

      return cleanedDeviceImages;
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
