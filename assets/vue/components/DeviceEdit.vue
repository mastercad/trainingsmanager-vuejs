<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Device Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origSeoLink"
          type="text"
          placeholder="Device Seo Link (automatically generated)"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origPreviewPicturePath"
          type="text"
          placeholder="Device picture preview path"
          class="form-control"
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
            v-bind:key="currentSelectedDeviceOption+option.key"
            :active="option.isActive"
            @click="saveSelection(option, deviceOption)"
          >
            {{ option.value }}
          </b-dropdown-item>
        </b-dropdown>
        <div v-else>
          <span>{{ deviceOption.name }}</span>:
          <input
            v-model="currentSelectedDeviceOptions[deviceOption.origOption.id]"
            class="form-control"
            :placeholder="deviceOption.placeholder"
            type="text"
          >
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <form
          enctype="multipart/form-data"
          novalidate
        >
          <h1>Upload images</h1>
          <div class="dropbox">
            <input
              type="file"
              multiple
              accept="image/*"
              class="input-file"
              :name="uploadFieldName"
              :disabled="isSaving"
              @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
            >
            <p v-if="isInitial">
              Drag your file(s) here to begin<br> or click to browse
            </p>
            <p v-if="isSaving">
              Uploading {{ fileCount }} files...
            </p>
          </div>
        </form>
      </div>
    </div>
    <div
      v-if="deviceImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in deviceImages"
        :key="key"
        class="col-3"
      >
        <img
          class="img-thumbnail"
          :src="image"
          v-bind:class="{ previewActive: previewPictureKey == key || extractFileName(image) === origPreviewPicturePath }"
          @click="setImageAsPreview(key)"
        >
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDeviceImage(key)"
        >
          Delete
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateDevice()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createDevice()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDevice()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { upload } from '../controllers/device-file-upload-service.js';
import deviceOptions from '../controllers/deviceOptions.js';
import OptionFunctions from "../shared/optionFunctions.js";

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

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
    selectedDeviceOptions: {
      type: Object,
      default: () => { return {}; }
    }
  },
  data() {
    return {
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null,
      uploadFieldName: 'deviceImage',
      previewPictureKey: null,
      origId: this.id,
      fileCount: 0,
      origName: this.name,
      origSeoLink: this.seoLink,
      origPreviewPicturePath: this.previewPicturePath,
      currentDeviceOptions: this.existingDeviceOptions,
      currentPossibleDeviceOptions: this.possibleDeviceOptions,
      currentSelectedDeviceOptions: this.selectedDeviceOptions,
      currentSelectedDeviceOption: 0
    }
  },
  computed: {
    prepareDeviceOptions () {
      return OptionFunctions.generateCurrentOptions(
        this.origId,
        OptionFunctions.prepareOptionCollection(this.currentPossibleDeviceOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;}),
        this.currentSelectedDeviceOptions
//        OptionFunctions.prepareOptionCollection(this.currentDeviceOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;})
      );
    },
    isInitial() {
      return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    },
    deviceImages() {
      return this.$store.getters["devices/deviceImages"];
    }
  },
  created() {
    this.$store.dispatch("devices/loadImages", this.id);
    this.currentSelectedDeviceOptions = {};
    for (let currentDeviceOptionPosition in this.currentDeviceOptions) {
      this.currentSelectedDeviceOptions[this.currentDeviceOptions[currentDeviceOptionPosition].deviceOption.id] = this.currentDeviceOptions[currentDeviceOptionPosition].deviceOptionValue;
    }
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createDevice() {
      const selectedDeviceOptions = this.generateSelectedDeviceOptionsForSave();
      const result = await this.$store.dispatch("devices/create",
        {
          name: this.origName,
          seoLink: this.origSeoLink,
          previewPicturePath: this.origPreviewPicturePath,
          deviceXDeviceOptions: selectedDeviceOptions
        }
      );

      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.previewPicturePath = "";
      }
    },
    async updateDevice() {
      const selectedDeviceOptions = this.generateSelectedDeviceOptionsForSave();
      const result = await this.$store.dispatch(
        "devices/update",
        {
          id: this.id,
          name: this.origName,
          seoLink: this.origSeoLink,
          previewPicturePath: this.origPreviewPicturePath,
          deviceXDeviceOptions: selectedDeviceOptions
        }
      );

      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.previewPicturePath = "";
      }
    },
    async deleteDevice() {
      const result = await this.$store.dispatch("devices/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.previewPicturePath = "";
      }
    },
    async deleteDeviceImage(key) {
      let imagePath = this.deviceImages[key];
      let fileName = btoa(this.extractFileName(imagePath));
      let result = null;

      if (imagePath.match(/^\/uploads\//)) {
        result = await this.$store.dispatch("devices/deleteUploadImage", fileName);
      } else {
        result = await this.$store.dispatch("devices/deleteDeviceImage", {fileName: fileName, id: this.origId});
      }

      if (result !== null) {
        this.$delete(this.deviceImages, key);
      }
    },
    setImageAsPreview(key) {
      this.origPreviewPicturePath = this.extractFileName(this.deviceImages[key]);
      this.previewPictureKey = key;
    },
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
      this.uploadedFiles = [];
      this.uploadError = null;
    },
    save(formData) {
      // upload data to the server
      this.currentStatus = STATUS_SAVING;
      let me = this;

      upload(formData)
        .then(function() {
          me.$store.dispatch("devices/loadImages", me.id);
          me.currentStatus = STATUS_SUCCESS;
        })
        .catch(err => {
          me.uploadError = err.response;
          me.currentStatus = STATUS_FAILED;
        });
    },
    filesChange(fieldName, fileList) {
      // handle file changes
      const formData = new FormData();

      if (!fileList.length) return;

      // append the files to FormData
      Array
        .from(Array(fileList.length).keys())
        .map(x => {
          formData.append(fieldName+"[]", fileList[x], fileList[x].name);
        });

      // save it
      this.save(formData);
    },
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    },
    saveSelection(option, preparedDeviceOption) {
      preparedDeviceOption.parts.forEach( part => {
        part.isActive = false;
        preparedDeviceOption.value = null;
      });

      preparedDeviceOption.value = option.value;
      this.currentSelectedDeviceOptions[preparedDeviceOption.origOption.id] = option.value;
      option.isActive = true;
      preparedDeviceOption.bindKey = option.key+'_'+option.value;
      preparedDeviceOption.name = preparedDeviceOption.origOption.origEntry.name+" ("+option.value+")";
      // this setting is important to change the whole key and force refresh of dropdown rendering
      this.currentSelectedDeviceOption=option.key
    },
    generateSelectedDeviceOptionsForSave() {
      let selectedDeviceOptions = [];
      let deviceOption = null;
      for (let currentSelectedDeviceOptionPosition in this.currentSelectedDeviceOptions) {
        for (let possibleDeviceOptionPosition in this.currentPossibleDeviceOptions) {
          if (this.currentPossibleDeviceOptions[possibleDeviceOptionPosition]["id"] == currentSelectedDeviceOptionPosition) {
            deviceOption = this.currentPossibleDeviceOptions[possibleDeviceOptionPosition];
            break;
          }
        }
        let deviceOptionValue = this.currentSelectedDeviceOptions[currentSelectedDeviceOptionPosition];

        if (deviceOption
          && "" !== deviceOptionValue
        ) {
          let existingDeviceOption = null;

          for (let currentExistingDeviceOptionPosition in this.existingDeviceOptions) {
            let currentExistingDeviceOption = this.existingDeviceOptions[currentExistingDeviceOptionPosition];
            if (currentExistingDeviceOption.deviceOption.id === deviceOption.id) {
              existingDeviceOption = currentExistingDeviceOption;
            }
          }

          let selectedDeviceOption = {
            id: existingDeviceOption ? existingDeviceOption.id : null,
            deviceOptionValue: deviceOptionValue,
            deviceOption: '/api/device_options/'+deviceOption.id
          };

          if (this.origId) {
            selectedDeviceOption.device = '/api/devices/'+this.origId;
          }

          selectedDeviceOptions.push(selectedDeviceOption);
        }
      }

      return selectedDeviceOptions;
    }
  }
};
</script>

<!-- SASS styling -->
<style lang="scss">
  .dropbox {
    outline: 2px dashed grey; /* the dash box */
    outline-offset: -10px;
    background: lightcyan;
    color: dimgray;
    padding: 10px 10px;
    min-height: 200px; /* minimum height */
    position: relative;
    cursor: pointer;
  }

  .input-file {
    opacity: 0; /* invisible but it's there! */
    width: 100%;
    height: 200px;
    position: absolute;
    cursor: pointer;
  }

  .dropbox:hover {
    background: lightblue; /* when mouse over to the drop zone, change color */
  }

  .dropbox p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 0;
  }

  .previewActive {
    outline: 2px solid yellow;
  }
</style>