<template>
  <b-container fluid>

    <b-card bg-variant="light" class="shadow p-2 mb-3 bg-white rounded">
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
              v-model="exercise.name"
              type="text"
              placeholder="Exercise Name"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_seo_link"
              v-model="exercise.seoLink"
              type="text"
              placeholder="Exercise Seo Link (automatically generated)"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_preview_picture_path'">Preview picture path:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="exercise_preview_picture_path"
              v-model="exercise.previewPicturePath"
              type="text"
              placeholder="Exercise Preview Picture Path"
              class="form-control"
              required
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_description'">Description:</label>
          </b-col>
          <b-col sm="9">
            <b-form-textarea
              id="exercise_description"
              v-model="exercise.description"
              type="text"
              placeholder="Exercise Description"
              class="form-control"
              required
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'exercise_special_features'">Special Features:</label>
          </b-col>
          <b-col sm="9">
            <b-form-textarea
              id="exercise_special_features"
              v-model="exercise.specialFeatures"
              type="text"
              placeholder="Exercise Special Features"
              class="form-control"
              required
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
        </b-row>
      </b-form-group>
    </b-card>

    <b-card bg-variant="light" class="mt-2 shadow p-2 mb-3 bg-white rounded">
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
            :text="exercise.exerciseXExerciseType[0] && exercise.exerciseXExerciseType[0].exerciseType ? exercise.exerciseXExerciseType[0].exerciseType.name : 'None'"
          >
            <b-dropdown-item
              :id="'exercise_type_null'"
              v-bind:key="'exercise_type_null'"
              :active="0 === exercise.exerciseXExerciseType[0].length"
              @click="saveExerciseTypeSelection(null)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="possibleExerciseType in exerciseTypes"
              :id="'exercise_type_'+possibleExerciseType.id"
              v-bind:key="'exercise_type_'+possibleExerciseType.id"
              :active="exercise.exerciseXExerciseType[0] && exercise.exerciseXExerciseType[0].exerciseType && exercise.exerciseXExerciseType[0].exerciseType.id == possibleExerciseType.id"
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
    >
    </options-for-edit>

    <b-card bg-variant="light" class="mt-2 shadow p-2 mb-3 bg-white rounded">
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
            :text="exercise.exerciseXDevices[0] && exercise.exerciseXDevices[0].device ? exercise.exerciseXDevices[0].device.name : 'None'"
          >
            <b-dropdown-item
              :id="'device_null'"
              v-bind:key="'device_null'"
              :active="0 === exercise.exerciseXDevices.length"
              @click="saveDeviceSelection(null)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="possibleDevice in devices"
              :id="'device_'+possibleDevice.id"
              v-bind:key="'device_'+possibleDevice.id"
              :active="exercise.exerciseXDevices[0] && exercise.exerciseXDevices[0].device && exercise.exerciseXDevices[0].device.id == possibleDevice.id"
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
      :current-options="exercise.exerciseXDeviceOptions"
      :additional-options="exercise.exerciseXDevices[0] ? exercise.exerciseXDevices[0].device.deviceXDeviceOptions : []"
      :identifier="exercise.id"
      description="Possible Device Options for this Exercise"
    >
    </options-for-edit>

    <b-card bg-variant="light" class="mt-2 shadow p-2 mb-3 bg-white rounded">
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
        v-for="(image, key) in exerciseImages"
        v-if="extractFileName(image) !== exercise.previewPicturePath"
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

    <b-card-group deck bg-variant="light" class="mt-2">
      <b-card v-if="isValidId(exercise.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          v-if="isValidId(exercise.id)"
          :disabled="exercise.name.length === 0 || exercise.description.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateExercise()"
        >
          Update
        </button>
      </b-card>

      <b-card v-if="isGenericId(exercise.id)" class="shadow p-2 mb-3 bg-white rounded">
        <button
          :disabled="exercise.name.length === 0 || exercise.description.length === 0"
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
      fileCount: 0
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
    }
  },
  created() {
    this.$store.dispatch("exercises/loadImages", this.exercise.id);
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
      const result = await this.$store.dispatch("exercises/create",
        {
          id: this.exercise.id,
          name: this.exercise.name,
          description: this.exercise.description,
          seoLink: this.exercise.seoLink,
          specialFeatures: this.exercise.specialFeatures,
          previewPicturePath: this.exercise.previewPicturePath,
          exerciseXDeviceOptions: this.exercise.exerciseXDeviceOptions,
          exerciseXExerciseOptions: this.exercise.exerciseXExerciseOptions,
          exerciseXDevices: this.exercise.exerciseXDevices,
          exerciseXExerciseType: this.exercise.exerciseXExerciseType
        }
      );
    },
    async updateExercise() {
      const result = await this.$store.dispatch(
        "exercises/update",
        {
          id: this.exercise.id,
          name: this.exercise.name,
          description: this.exercise.description,
          seoLink: this.exercise.seoLink,
          specialFeatures: this.exercise.specialFeatures,
          previewPicturePath: this.exercise.previewPicturePath,
          exerciseXDeviceOptions: this.exercise.exerciseXDeviceOptions,
          exerciseXExerciseOptions: this.exercise.exerciseXExerciseOptions,
          exerciseXDevices: this.exercise.exerciseXDevices,
          exerciseXExerciseType: this.exercise.exerciseXExerciseType
        }
      );
    },
    async deleteExercise() {
      const result = await this.$store.dispatch("exercises/delete", this.exercise.id);
    },
    async deleteExerciseImage(key) {
      let imagePath = this.exerciseImages[key];
      let fileName = btoa(this.extractFileName(imagePath));
      let result = null;

      if (imagePath.match(/^\/uploads\//)) {
        result = await this.$store.dispatch("exercises/deleteUploadImage", fileName);
      } else {
        result = await this.$store.dispatch("exercises/deleteExerciseImage", {fileName: fileName, id: this.exercise.id});
      }

      if (result !== null) {
        this.$delete(this.exerciseImages, key);
      }
    },
    setImageAsPreview(key) {
      this.exercise.previewPicturePath = this.extractFileName(this.exerciseImages[key]);
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
    saveDeviceSelection(device)
    {
      if (null === device) {
        this.exercise.exerciseXDevices = [];
        return;
      }

      if (this.exercise.exerciseXDevices[0]
        && this.exercise.exerciseXDevices[0].device.id === device.id
      ) {
        return;
      }

      if (this.exercise.exerciseXDevices[0]) {
        this.exercise.exerciseXDevices[0]['device'] = device;
        return;
      }

      this.exercise.exerciseXDevices = [];
      let exerciseXDevice = {
        'device': device
      };

      if (this.isValidId(this.exercise.id)) {
        exerciseXDevice['exercise'] = '/api/exercises/'+this.exercise.id
      }

      this.exercise.exerciseXDevices.push(exerciseXDevice);
    },
    saveExerciseTypeSelection(exerciseType)
    {
      if (null === exerciseType) {
        this.exercise.exerciseXExerciseType = [];
        return;
      }

      if (this.exercise.exerciseXExerciseType[0]
        && this.exercise.exerciseXExerciseType[0].exerciseType.id === exerciseType.id
      ) {
        return;
      }

      if (this.exercise.exerciseXExerciseType[0]) {
        this.exercise.exerciseXExerciseType[0]['exerciseType'] = exerciseType;
        return;
      }

      this.exercise.exerciseXExerciseType = [];
      let exerciseXExerciseType = {
        'exerciseType': exerciseType
      };

      if (this.isValidId(this.exercise.id)) {
        exerciseXExerciseType['exercise'] = '/api/exercises/'+this.exercise.id
      }

      this.exercise.exerciseXExerciseType.push(exerciseXExerciseType);
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