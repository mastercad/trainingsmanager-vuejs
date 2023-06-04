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
            v-bind:key="currentSelectedDeviceOption.id+'_'+option.key"
            :active="option.isActive"
            @click="saveSelection(currentSelectedOptions, option, deviceOption)"
          >
            {{ option.value }}
          </b-dropdown-item>
        </b-dropdown>
        <div v-else>
          <span>{{ deviceOption.name }}</span>:
          <input
            v-model="currentSelectedTrainingPlanDeviceOptions[deviceOption.origOption.id]"
            class="form-control"
            :placeholder="deviceOption.placeholder"
            type="text"
          >
        </div>
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

import OptionFunctions from "../shared/optionFunctions.js";

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
    possibleDeviceOptions: {
      type: Array,
      required: true
    },
    existingDeviceOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origSeoLink: this.seoLink,
      origPreviewPicturePath: this.previewPicturePath,
      currentDeviceOptions: this.existingDeviceOptions,
      currentPossibleDeviceOptions: this.possibleDeviceOptions,
      currentSelectedOptions: this.selectedOptions
    }
  },
  computed: {
    prepareDeviceOptions () {
      return OptionFunctions.generateCurrentOptions(
        undefined !== this.currentSelectedDevice ? this.currentSelectedDevice.id : null,
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
    this.$store.dispatch("devices/loadImages", this.id);
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
