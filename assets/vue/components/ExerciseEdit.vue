<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-4">
        <input
          v-model="origName"
          type="text"
          placeholder="Exercise Name"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origSeoLink"
          type="text"
          placeholder="Exercise Seo Link (automatically generated)"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origDescription"
          type="text"
          placeholder="Exercise Description"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origSpecialFeatures"
          type="text"
          placeholder="Exercise Special Features"
          class="form-control"
        >
      </div>
      <div class="col-md-4">
        <input
          v-model="origPreviewPicturePath"
          type="text"
          placeholder="Exercise picture preview path"
          class="form-control"
        >
      </div>
    </div>

    <div class="row">
      <b-dropdown
        split-variant="outline-primary"
        variant="primary"
        class="m-md-2"
        :text="currentSelectedExerciseType && currentSelectedExerciseType[0] && currentSelectedExerciseType[0].exerciseType ? 'select exercise type ('+currentSelectedExerciseType[0].exerciseType.name+')' : 'select exercise type'"
      >
        <b-dropdown-item
          :id="'exercise_type_null'"
          v-bind:key="'exercise_type_null'"
          :active="currentSelectedExerciseType == null"
          @click="saveExerciseTypeSelection(null)"
        >
          None
        </b-dropdown-item>

        <b-dropdown-item
          v-for="possibleExerciseType in exerciseTypes"
          :id="'exercise_type_'+possibleExerciseType.id"
          v-bind:key="'exercise_type_'+possibleExerciseType.id"
          :active="currentSelectedExerciseType[0] && currentSelectedExerciseType[0].exerciseType && currentSelectedExerciseType[0].exerciseType.id == possibleExerciseType.id"
          @click="saveExerciseTypeSelection(possibleExerciseType)"
        >
          {{ possibleExerciseType.name }}
        </b-dropdown-item>
      </b-dropdown>
    </div>

    <div class="row">
      <b-dropdown
        split-variant="outline-primary"
        variant="primary"
        class="m-md-2"
        :text="currentSelectedDevice[0] && currentSelectedDevice[0].device ? 'select device ('+currentSelectedDevice[0].device.name+')' : 'select device'"
      >
        <b-dropdown-item
          :id="'device_null'"
          v-bind:key="'device_null'"
          :active="currentSelectedDevice == null"
          @click="saveDeviceSelection(null)"
        >
          None
        </b-dropdown-item>

        <b-dropdown-item
          v-for="possibleDevice in devices"
          :id="'device_'+possibleDevice.id"
          v-bind:key="'device_'+possibleDevice.id"
          :active="currentSelectedDevice[0] && currentSelectedDevice[0].device && currentSelectedDevice[0].device.id == possibleDevice.id"
          @click="saveDeviceSelection(possibleDevice)"
        >
          {{ possibleDevice.name }}
        </b-dropdown-item>
      </b-dropdown>
    </div>

    <div class="row">
      <h2>
        Exercise Options
      </h2>
      <div class="flex-grid">
        <div
          v-for="exerciseOption in prepareExerciseOptions"
          :id="'exercise_option_'+exerciseOption.key"
          :key="'exercise_option_'+exerciseOption.key"
        >
          <b-dropdown
            v-if="exerciseOption.isMultipartOption"
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="exerciseOption.name"
          >
            <b-dropdown-item
              v-for="option in exerciseOption.parts"
              :id="'exercise_option_'+option.key"
              :key="currentSelectedExerciseOption+'_exercise_option_'+option.key"
              :active="option.isActive"
              @click="saveExerciseOptionSelection(option, exerciseOption)"
            >
              {{ option.value }}
            </b-dropdown-item>
          </b-dropdown>
          <div v-else>
            <span>{{ exerciseOption.name }}</span>:
            <input
              v-model="currentSelectedExerciseOptions[exerciseOption.origOption.id]"
              class="form-control"
              :placeholder="exerciseOption.placeholder"
              type="text"
            >
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <h2>
        Device Options
      </h2>

      <div class="flex-grid">
        <div
          v-for="deviceOption in prepareDeviceOptions"
          :id="'device_option_'+deviceOption.key"
          :key="'device_option_'+deviceOption.key"
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
              :key="currentSelectedDeviceOption+'_device_option_'+option.key"
              :active="option.isActive"
              @click="saveDeviceOptionSelection(option, deviceOption)"
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
    </div>

    <div class="row">
      <h2>Upload images</h2>
      <div class="col-md-4">
        <form
          enctype="multipart/form-data"
          novalidate
        >
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
      v-if="exerciseImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in exerciseImages"
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
          @click="deleteExerciseImage(key)"
        >
          Delete
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <button
          v-if="origId"
          :disabled="origName.length === 0 || origDescription.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExercise()"
        >
          Update
        </button>
        <button
          v-if="!origId"
          :disabled="origName.length === 0 || origDescription.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExercise()"
        >
          Create
        </button>
      </div>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExercise()"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { upload } from '../controllers/exercise-file-upload-service.js';
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
    possibleDeviceOptions: {
      type: Array,
      required: true
    },
    existingDeviceOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    possibleExerciseOptions: {
      type: Array,
      required: true
    },
    existingExerciseOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    selectedDevice: {
      type: Array,
      default: () => { return new Array(); }
    },
    selectedExerciseType: {
      type: Array,
      default: () => { return new Array(); }
    }
  },
  data() {
    return {
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null,
      uploadFieldName: 'exerciseImage',
      previewPictureKey: null,
      origId: this.id,
      fileCount: 0,
      origName: this.name,
      origDescription: this.description,
      origSeoLink: this.seoLink,
      origSpecialFeatures: this.specialFeatures,
      origPreviewPicturePath: this.previewPicturePath,
      currentDeviceOptions: this.existingDeviceOptions,
      currentPossibleDeviceOptions: this.possibleDeviceOptions,
      currentExerciseOptions: this.existingExerciseOptions,
      currentPossibleExerciseOptions: this.possibleExerciseOptions,
      currentSelectedDeviceOptions: this.selectedDeviceOptions,
      currentSelectedDeviceOption: 0,
      currentSelectedExerciseOptions: this.selectedExerciseOptions,
      currentSelectedExerciseOption: 0,
      currentSelectedDevice: this.selectedDevice,
      currentSelectedExerciseType: this.selectedExerciseType
    }
  },
  computed: {
    prepareDeviceOptions () {
      return OptionFunctions.generateCurrentOptions(
        this.origId,
        OptionFunctions.prepareOptionCollection(this.currentPossibleDeviceOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;}),
        this.currentSelectedDeviceOptions
      );
    },
    prepareExerciseOptions () {
      return OptionFunctions.generateCurrentOptions(
        this.origId,
        OptionFunctions.prepareOptionCollection(this.currentPossibleExerciseOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;}),
        this.currentSelectedExerciseOptions
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
    exerciseImages() {
      return this.$store.getters["exercises/exerciseImages"];
    },
    devices() {
      return this.$store.getters["devices/devices"];
    },
    exerciseTypes() {
      return this.$store.getters["exerciseTypes/exerciseTypes"];
    }
  },
  created() {
    this.$store.dispatch("exercises/loadImages", this.id);
    this.$store.dispatch("devices/findAll");
    this.$store.dispatch("exerciseTypes/findAll");

    this.currentSelectedDeviceOptions = {};
    for (let currentDeviceOptionPosition in this.currentDeviceOptions) {
      this.currentSelectedDeviceOptions[this.currentDeviceOptions[currentDeviceOptionPosition].deviceOption.id] = this.currentDeviceOptions[currentDeviceOptionPosition].deviceOptionValue;
    }
    this.currentSelectedExerciseOptions = {};
    for (let currentExerciseOptionPosition in this.currentExerciseOptions) {
      this.currentSelectedExerciseOptions[this.currentExerciseOptions[currentExerciseOptionPosition].exerciseOption.id] = this.currentExerciseOptions[currentExerciseOptionPosition].exerciseOptionValue;
    }
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createExercise() {
      const selectedDeviceOptions = this.generateSelectedDeviceOptionsForSave();
      const selectedExerciseOptions = this.generateSelectedExerciseOptionsForSave();
      const selectedDevice = this.generateSelectedDeviceForSave();
      const selectedExerciseType = this.generateSelectedExerciseTypeForSave();
      const result = await this.$store.dispatch("exercises/create",
        {
          name: this.origName,
          description: this.origDescription,
          seoLink: this.origSeoLink,
          specialFeatures: this.origSpecialFeatures,
          previewPicturePath: this.origPreviewPicturePath,
          exerciseXDeviceOptions: selectedDeviceOptions,
          exerciseXExerciseOptions: selectedExerciseOptions,
          exerciseXDevices: selectedDevice,
          exerciseXExerciseType: selectedExerciseType
        }
      );

      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.seoLink = "";
        this.$data.description = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
        this.$data.exerciseXDeviceOptions = [];
        this.$data.exerciseXExerciseOptions = [];
        this.$data.exerciseXDevice = [];
        this.$data.exerciseXExerciseType = [];
      }
    },
    async updateExercise() {
      const selectedDeviceOptions = this.generateSelectedDeviceOptionsForSave();
      const selectedExerciseOptions = this.generateSelectedExerciseOptionsForSave();
      const selectedDevice = this.generateSelectedDeviceForSave();
      const selectedExerciseType = this.generateSelectedExerciseTypeForSave();

      const result = await this.$store.dispatch(
        "exercises/update",
        {
          id: this.id,
          name: this.origName,
          description: this.origDescription,
          seoLink: this.origSeoLink,
          specialFeatures: this.origSpecialFeatures,
          previewPicturePath: this.origPreviewPicturePath,
          exerciseXDeviceOptions: selectedDeviceOptions,
          exerciseXExerciseOptions: selectedExerciseOptions,
          exerciseXDevices: selectedDevice,
          exerciseXExerciseType: selectedExerciseType
        }
      );

      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.description = "";
        this.$data.seoLink = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
        this.$data.exerciseXDeviceOptions = [];
        this.$data.exerciseXExerciseOptions = [];
        this.$data.exerciseXDevice = [];
        this.$data.exerciseXExerciseType = [];
      }
    },
    async deleteExercise() {
      const result = await this.$store.dispatch("exercises/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.description = "";
        this.$data.seoLink = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
      }
    },
    async deleteExerciseImage(key) {
      let imagePath = this.exerciseImages[key];
      let fileName = btoa(this.extractFileName(imagePath));
      let result = null;

      if (imagePath.match(/^\/uploads\//)) {
        result = await this.$store.dispatch("exercises/deleteUploadImage", fileName);
      } else {
        result = await this.$store.dispatch("exercises/deleteExerciseImage", {fileName: fileName, id: this.origId});
      }

      if (result !== null) {
        this.$delete(this.exerciseImages, key);
      }
    },
    setImageAsPreview(key) {
      this.origPreviewPicturePath = this.extractFileName(this.exerciseImages[key]);
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
          me.$store.dispatch("exercises/loadImages", me.id);
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
    saveDeviceOptionSelection(option, preparedDeviceOption) {
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
    saveExerciseOptionSelection(option, preparedExerciseOption) {
      preparedExerciseOption.parts.forEach( part => {
        part.isActive = false;
        preparedExerciseOption.value = null;
      });

      preparedExerciseOption.value = option.value;
      this.currentSelectedExerciseOptions[preparedExerciseOption.origOption.id] = option.value;
      option.isActive = true;
      preparedExerciseOption.bindKey = option.key+'_'+option.value;
      preparedExerciseOption.name = preparedExerciseOption.origOption.origEntry.name+" ("+option.value+")";
      // this setting is important to change the whole key and force refresh of dropdown rendering
      this.currentSelectedExerciseOption=option.key
    },
    saveDeviceSelection(device)
    {
      if (null === device) {
        this.currentSelectedDevice = [];
        return;
      }

      if (this.currentSelectedDevice[0]
        && this.currentSelectedDevice[0].device.id === device.id
      ) {
        return;
      }

      if (this.currentSelectedDevice[0]) {
        this.currentSelectedDevice[0]['device'] = device;
        return;
      }

      this.currentSelectedDevice = [];
      let exerciseXDevice = {
        'device': device
      };

      if (this.origId) {
        exerciseXDevice['exercise'] = '/api/exercises/'+this.origId
      }

      this.currentSelectedDevice.push(exerciseXDevice);
    },
    saveExerciseTypeSelection(exerciseType)
    {
      if (null === exerciseType) {
        this.currentSelectedExerciseType = [];
        return;
      }

      if (this.currentSelectedExerciseType[0]
        && this.currentSelectedExerciseType[0].exerciseType.id === exerciseType.id
      ) {
        return;
      }

      if (this.currentSelectedExerciseType[0]) {
        this.currentSelectedExerciseType[0]['exerciseType'] = exerciseType;
        return;
      }

      this.currentSelectedExerciseType = [];
      let exerciseXExerciseType = {
        'exerciseType': exerciseType
      };

      if (this.origId) {
        exerciseXExerciseType['exercise'] = '/api/exercises/'+this.origId
      }

      this.currentSelectedExerciseType.push(exerciseXExerciseType);
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
            selectedDeviceOption.exercise = '/api/exercises/'+this.origId;
          }

          selectedDeviceOptions.push(selectedDeviceOption);
        }
      }

      return selectedDeviceOptions;
    },
    generateSelectedExerciseOptionsForSave() {
      let selectedExerciseOptions = [];
      let exerciseOption = null;
      for (let currentSelectedExerciseOptionPosition in this.currentSelectedExerciseOptions) {
        for (let possibleExerciseOptionPosition in this.currentPossibleExerciseOptions) {
          if (this.currentPossibleExerciseOptions[possibleExerciseOptionPosition]["id"] == currentSelectedExerciseOptionPosition) {
            exerciseOption = this.currentPossibleExerciseOptions[possibleExerciseOptionPosition];
            break;
          }
        }
        let exerciseOptionValue = this.currentSelectedExerciseOptions[currentSelectedExerciseOptionPosition];

        if (exerciseOption
          && "" !== exerciseOptionValue
        ) {
          let existingExerciseOption = null;

          for (let currentExistingExerciseOptionPosition in this.existingExerciseOptions) {
            let currentExistingExerciseOption = this.existingExerciseOptions[currentExistingExerciseOptionPosition];
            if (currentExistingExerciseOption.exerciseOption.id === exerciseOption.id) {
              existingExerciseOption = currentExistingExerciseOption;
            }
          }

          let selectedExerciseOption = {
            id: existingExerciseOption ? existingExerciseOption.id : null,
            exerciseOptionValue: exerciseOptionValue,
            exerciseOption: '/api/exercise_options/'+exerciseOption.id
          };

          if (this.origId) {
            selectedExerciseOption.exercise = '/api/exercises/'+this.origId;
          }

          selectedExerciseOptions.push(selectedExerciseOption);
        }
      }

      return selectedExerciseOptions;
    },
    generateSelectedDeviceForSave()
    {
      let exerciseXDevices = [];
      let exerciseXDevice = {};

      if (this.currentSelectedDevice
        && this.currentSelectedDevice[0]
      ) {
        exerciseXDevice['device'] = '/api/devices/'+this.currentSelectedDevice[0].device.id;
        exerciseXDevice['exercise'] = this.origId ? '/api/exercises/'+this.origId : null;

        if( this.currentSelectedDevice[0].id) {
          exerciseXDevice['id'] = this.currentSelectedDevice[0].id;
        }

        exerciseXDevices.push(exerciseXDevice);
      }

      return exerciseXDevices;
    },
    generateSelectedExerciseTypeForSave()
    {
      let exerciseXExerciseTypes = [];
      let exerciseXExerciseType = {};

      if (this.currentSelectedExerciseType
        && this.currentSelectedExerciseType[0]
      ) {
        exerciseXExerciseType['exerciseType'] = '/api/exercise_types/'+this.currentSelectedExerciseType[0].exerciseType.id;
        exerciseXExerciseType['exercise'] = this.origId ? '/api/exercises/'+this.origId : null;

        if( this.currentSelectedExerciseType[0].id) {
          exerciseXExerciseType['id'] = this.currentSelectedExerciseType[0].id;
        }

        exerciseXExerciseTypes.push(exerciseXExerciseType);
      }

      return exerciseXExerciseTypes;
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