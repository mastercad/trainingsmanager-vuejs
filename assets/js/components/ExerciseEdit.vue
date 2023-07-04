<template>
  <b-container fluid>
    <b-card
      bg-variant="light"
      class="shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="2"
        label="Exercise"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this exercise"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'exercise_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_name"
              v-model="origExercise.name"
              type="text"
              placeholder="Exercise Name"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_seo_link"
              v-model="origExercise.seoLink"
              type="text"
              placeholder="Exercise Seo Link (automatically generated)"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_preview_picture_path'">Preview picture path:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_preview_picture_path"
              v-model="origExercise.previewPicturePath"
              type="text"
              placeholder="Exercise Preview Picture Path"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_description'">Description:</label>
          </b-col>
          <b-col sm="9">
            <b-form-textarea
              id="exercise_description"
              v-model="origExercise.description"
              type="text"
              placeholder="Exercise Description"
              class="form-control"
              required
              rows="3"
              max-rows="6"
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_special_features'">Special Features:</label>
          </b-col>
          <b-col sm="9">
            <b-form-textarea
              id="exercise_special_features"
              v-model="origExercise.specialFeatures"
              type="text"
              placeholder="Exercise Special Features"
              class="form-control"
              required
              rows="3"
              max-rows="6"
            />
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="6"
        label="Exercise Type"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="responsible exercise type"
      >
        <b-col sm="9">
          <b-dropdown
            id="exercise_type"
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="origExercise.exerciseXExerciseType[0] && origExercise.exerciseXExerciseType[0].exerciseType ? origExercise.exerciseXExerciseType[0].exerciseType.name : 'None'"
          >
            <b-dropdown-item
              :id="'exercise_type_null'"
              :key="'exercise_type_null'"
              :active="undefined === origExercise.exerciseXExerciseType[0] || 0 === origExercise.exerciseXExerciseType[0].length"
              @click="saveExerciseTypeSelection(null)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="possibleExerciseType in exerciseTypes"
              :id="'exercise_type_'+possibleExerciseType.id"
              :key="'exercise_type_'+possibleExerciseType.id"
              :active="origExercise.exerciseXExerciseType[0] && origExercise.exerciseXExerciseType[0].exerciseType && origExercise.exerciseXExerciseType[0].exerciseType.id == possibleExerciseType.id"
              @click="saveExerciseTypeSelection(possibleExerciseType)"
            >
              {{ possibleExerciseType.name }}
            </b-dropdown-item>
          </b-dropdown>
        </b-col>
      </b-form-group>
    </b-card>

    <options-for-edit
      type="exercise"
      :possible-options="possibleExerciseOptions"
      :current-options="exercise.exerciseXExerciseOptions"
      :identifier="exercise.id"
      description="Possible Exercise Options for this Exercise"
    />

    <b-card
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="6"
        label="Device"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="responsible device"
      >
        <b-col sm="9">
          <b-dropdown
            id="device"
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="origExercise.exerciseXDevices[0] && origExercise.exerciseXDevices[0].device ? origExercise.exerciseXDevices[0].device.name : 'None'"
          >
            <b-dropdown-item
              :id="'device_null'"
              :key="'device_null'"
              :active="0 === origExercise.exerciseXDevices.length"
              @click="saveDeviceSelection(null)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="possibleDevice in devices"
              :id="'device_'+possibleDevice.id"
              :key="'device_'+possibleDevice.id"
              :active="origExercise.exerciseXDevices[0] && origExercise.exerciseXDevices[0].device && origExercise.exerciseXDevices[0].device.id == possibleDevice.id"
              @click="saveDeviceSelection(possibleDevice)"
            >
              {{ possibleDevice.name }}
            </b-dropdown-item>
          </b-dropdown>
        </b-col>
      </b-form-group>
    </b-card>

    <options-for-edit
      type="device"
      :possible-options="possibleDeviceOptions"
      :current-options="origExercise.exerciseXDeviceOptions"
      :additional-options="origExercise.exerciseXDevices[0] ? origExercise.exerciseXDevices[0].device.deviceXDeviceOptions : []"
      :identifier="origExercise.id"
      description="Possible Device Options for this Exercise"
    />

    <b-card
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
    >
      <div class="col">
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
      </div>
    </b-card>

    <div
      v-if="exerciseImages"
      id="previews"
      class="row"
    >
      <div
        v-for="(image, key) in cleanedImages"
        :key="key"
        class="col-3 mt-2 shadow p-2 mb-3 bg-white rounded"
      >
        <img
          class="img-thumbnail"
          :src="image"
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

    <b-card-group
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-if="isValidId(origExercise.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          v-if="isValidId(origExercise.id)"
          :disabled="origExercise.name.length === 0 || origExercise.description.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExercise()"
        >
          Update
        </button>
      </b-card>

      <b-card
        v-if="isGenericId(origExercise.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          :disabled="origExercise.name.length === 0 || origExercise.description.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createExercise()"
        >
          Create
        </button>
      </b-card>

      <b-card class="shadow p-2 mb-3 bg-white rounded">
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteExercise()"
        >
          Delete
        </button>
      </b-card>
    </b-card-group>
  </b-container>
</template>

<script>
import { upload } from '../controllers/exercise-file-upload-service.js';
import OptionsForEdit from './OptionsForEdit.vue';

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  name: 'ExerciseEditView',
  components: {
    OptionsForEdit
  },
  props: {
    possibleDeviceOptions: {
      type: Array,
      required: true
    },
    possibleExerciseOptions: {
      type: Array,
      required: true
    },
    exercise: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null,
      uploadFieldName: 'exerciseImage',
      previewPictureKey: null,
      fileCount: 0,
      origExercise: this.exercise
    }
  },
  computed: {
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
    },
    cleanedImages() {
      if(this.isImagesLoading) {
        return [];
      }

      let cleanedImages = [];
      for(let imagePosition in this.exerciseImages) {
        let image = this.exerciseImages[imagePosition];
        if (this.extractFileName(image) !== this.exercise.previewPicturePath) {
          cleanedImages.push(image);
        }
      }

      return cleanedImages;
    }
  },
  created() {
    this.$store.dispatch("exercises/loadImages", this.origExercise.id);
    this.$store.dispatch("devices/findAll");
    this.$store.dispatch("exerciseTypes/findAll");
  },
  mounted() {
    this.reset();
  },
  beforeDestroy() {
    console.log("BEFORE DESTROY!");
    console.log(this.exercise);
  },
  methods: {
    async createExercise() {
      await this.$store.dispatch("exercises/create",
        {
          id: this.origExercise.id,
          name: this.origExercise.name,
          description: this.origExercise.description,
          seoLink: this.origExercise.seoLink,
          specialFeatures: this.origExercise.specialFeatures,
          previewPicturePath: this.origExercise.previewPicturePath,
          exerciseXDeviceOptions: this.origExercise.exerciseXDeviceOptions,
          exerciseXExerciseOptions: this.origExercise.exerciseXExerciseOptions,
          exerciseXDevices: this.origExercise.exerciseXDevices,
          exerciseXExerciseType: this.origExercise.exerciseXExerciseType
        }
      );
    },
    async updateExercise() {
      await this.$store.dispatch(
        "exercises/update",
        {
          id: this.origExercise.id,
          name: this.origExercise.name,
          description: this.origExercise.description,
          seoLink: this.origExercise.seoLink,
          specialFeatures: this.origExercise.specialFeatures,
          previewPicturePath: this.origExercise.previewPicturePath,
          exerciseXDeviceOptions: this.origExercise.exerciseXDeviceOptions,
          exerciseXExerciseOptions: this.origExercise.exerciseXExerciseOptions,
          exerciseXDevices: this.origExercise.exerciseXDevices,
          exerciseXExerciseType: this.origExercise.exerciseXExerciseType
        }
      );
    },
    async deleteExercise() {
      await this.$store.dispatch("exercises/delete", this.origExercise.id);
    },
    async deleteExerciseImage(key) {
      let imagePath = this.exerciseImages[key];
      let fileName = btoa(this.extractFileName(imagePath));
      let result = null;

      if (imagePath.match(/^\/uploads\//)) {
        result = await this.$store.dispatch("exercises/deleteUploadImage", fileName);
      } else {
        result = await this.$store.dispatch("exercises/deleteExerciseImage", {fileName: fileName, id: this.origExercise.id});
      }

      if (result !== null) {
        this.$delete(this.exerciseImages, key);
      }
    },
    setImageAsPreview(key) {
      this.origExercise.previewPicturePath = this.extractFileName(this.exerciseImages[key]);
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
          me.$store.dispatch("exercises/loadImages", me.origExercise.id);
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
        .map((x) => {
          formData.append(fieldName+"[]", fileList[x], fileList[x].name);
        });

      // save it
      this.save(formData);
    },
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    },
    saveDeviceSelection(device)
    {
      if (null === device) {
        this.origExercise.exerciseXDevices = [];
        return;
      }

      if (this.origExercise.exerciseXDevices[0]
        && this.origExercise.exerciseXDevices[0].device.id === device.id
      ) {
        return;
      }

      if (this.origExercise.exerciseXDevices[0]) {
        this.origExercise.exerciseXDevices[0]['device'] = device;
        return;
      }

      this.origExercise.exerciseXDevices = [];
      let exerciseXDevice = {
        'device': device
      };

      if (this.isValidId(this.exercise.id)) {
        exerciseXDevice['exercise'] = '/api/exercises/'+this.exercise.id
      }

      this.origExercise.exerciseXDevices.push(exerciseXDevice);
    },
    saveExerciseTypeSelection(exerciseType)
    {
      if (null === exerciseType) {
        this.origExercise.exerciseXExerciseType = [];
        return;
      }

      if (this.origExercise.exerciseXExerciseType[0]
        && this.origExercise.exerciseXExerciseType[0].exerciseType.id === exerciseType.id
      ) {
        return;
      }

      if (this.origExercise.exerciseXExerciseType[0]) {
        this.origExercise.exerciseXExerciseType[0]['exerciseType'] = exerciseType;
        return;
      }

      this.origExercise.exerciseXExerciseType = [];
      let exerciseXExerciseType = {
        'exerciseType': exerciseType
      };

      if (this.isValidId(this.origExercise.id)) {
        exerciseXExerciseType['exercise'] = '/api/exercises/'+this.origExercise.id
      }

      this.origExercise.exerciseXExerciseType.push(exerciseXExerciseType);
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('exercise_'));
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