<template>
  <b-container fluid>
    <b-card
      bg-variant="light"
      class="shadow p-2 mb-3 bg-white rounded"
    >
      <b-form-group
        label-cols-lg="3"
        label="Device"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="All main settings for this device"
      >
        <b-row>
          <b-col sm="3">
            <label :for="'device_name'">Name:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_name"
              v-model="origDevice.name"
              type="text"
              placeholder="Device Name"
              class="form-control"
              required
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'device_seo_link'">Seo Link:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_seo_link"
              v-model="origDevice.seoLink"
              type="text"
              placeholder="Device Seo Link (automatically generated)"
              class="form-control"
            />
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="3">
            <label :for="'device_preview_picture_path'">Preview Picture Path:</label>
          </b-col>
          <b-col sm="9">
            <b-form-input
              id="device_preview_picture_path"
              v-model="origDevice.previewPicturePath"
              type="text"
              placeholder="Device picture preview path"
              class="form-control"
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
        label="Device Group"
        label-size="lg"
        label-class="font-weight-bold pt-0"
        class="mb-0"
        description="responsible device group for this device"
      >
        <b-col sm="9">
          <b-dropdown
            id="device_device_group"
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="undefined !== origDevice.deviceXDeviceGroups && origDevice.deviceXDeviceGroups[0] && origDevice.deviceXDeviceGroups[0].device ? origDevice.deviceXDeviceGroups[0].deviceGroup.name : 'None'"
          >
            <b-dropdown-item
              :id="'device_group_null'"
              :key="'device_group_null'"
              :active="undefined === origDevice.deviceXDeviceGroups || undefined === origDevice.deviceXDeviceGroups[0]"
              @click="saveDeviceGroupSelection(null)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="possibleDeviceGroup in deviceGroups"
              :id="'device_group_'+possibleDeviceGroup.id"
              :key="'device_group_'+possibleDeviceGroup.id"
              :active="undefined !== origDevice.deviceXDeviceGroups && origDevice.deviceXDeviceGroups[0] && origDevice.deviceXDeviceGroups[0].device && origDevice.deviceXDeviceGroups[0].deviceGroup.id == possibleDeviceGroup.id"
              @click="saveDeviceGroupSelection(possibleDeviceGroup)"
            >
              {{ possibleDeviceGroup.name }}
            </b-dropdown-item>
          </b-dropdown>
        </b-col>
      </b-form-group>
    </b-card>

    <options-for-edit
      type="device"
      :possible-options="possibleDeviceOptions"
      :current-options="origDevice.deviceXDeviceOptions"
      :identifier="origDevice.id"
      description="Possible Device Options for this Device"
    />

    <b-card
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
    >
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
    </b-card>

    <b-card-group
      v-if="deviceImages && 0 < deviceImages.length"
      id="previews"
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-for="(image, key) in deviceImages"
        :key="key"
        no-body
        :img-src="image"
        img-alt="Image"
        img-top
        :class="{ previewActive: previewPictureKey == key || extractFileName(image) === origDevice.previewPicturePath }"
        @click="setImageAsPreview(key)"
      >
        <b-button
          class="mt-auto"
          variant="primary"
          @click="deleteDeviceImage(key)"
        >
          Delete
        </b-button>
      </b-card>
    </b-card-group>

    <b-card
      v-if="deviceImages && 0 < deviceImages.length"
      id="previews"
      bg-variant="light"
      class="mt-2 shadow p-2 mb-3 bg-white rounded"
    >
      <div
        v-for="(image, key) in cleanedDeviceImages"
        :key="'image_'+key"
        class="col-3"
      >
        <img
          class="img-thumbnail"
          :src="image"
          :alt="image"
          style="max-height: 200px;"
        >
        <button
          type="button"
          class="btn btn-primary"
          @click="deleteDeviceImage(key)"
        >
          Delete
        </button>
      </div>
      <img
        v-if="isImagesLoading"
        alt="Content loading..."
        src="/images/content/static/spinner.gif"
      >
    </b-card>

    <b-card-group
      deck
      bg-variant="light"
      class="mt-2"
    >
      <b-card
        v-if="isValidId(origDevice.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          id="button_save"
          :disabled="origDevice.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="updateDevice()"
        >
          Update
        </button>
      </b-card>

      <b-card
        v-if="isGenericId(origDevice.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          id="button_save"
          :disabled="origDevice.name.length === 0"
          type="button"
          class="btn btn-primary"
          @click="createDevice()"
        >
          Create
        </button>
      </b-card>

      <b-card
        v-if="isValidId(origDevice.id)"
        class="shadow p-2 mb-3 bg-white rounded"
      >
        <button
          id="button_delete"
          type="button"
          class="btn btn-primary"
          @click="deleteDevice()"
        >
          Delete
        </button>
      </b-card>
    </b-card-group>
  </b-container>
</template>

<script>
import { upload } from '../controllers/device-file-upload-service.js';
import OptionsForEdit from './OptionsForEdit.vue';

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

export default {
  name: "DeviceEditView",
  components: {
    OptionsForEdit
  },
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
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null,
      uploadFieldName: 'deviceImage',
      fileCount: 0,
      currentSelectedDeviceGroups: [],
      previewPictureKey: null,
      origDevice: this.device
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
    isImagesLoading() {
      return this.$store.getters["devices/isImagesLoading"];
    },
    deviceImages() {
      return this.$store.getters["devices/deviceImages"];
    },
    deviceGroups() {
      return this.$store.getters["deviceGroups/deviceGroups"];
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
    this.$store.dispatch("deviceGroups/findAll");
    this.$store.dispatch("devices/loadImages", this.device.id);
  },
  mounted() {
    this.reset();
  },
  methods: {
    async createDevice() {
      await this.$store.dispatch("devices/create",
        {
          id: this.origDevice.id,
          name: this.origDevice.name,
          seoLink: this.origDevice.seoLink,
          previewPicturePath: this.origDevice.previewPicturePath,
          deviceXDeviceOptions: this.origDevice.deviceXDeviceOptions,
          deviceXDeviceGroups: this.currentSelectedDeviceGroups
        }
      );
    },
    async updateDevice() {
      await this.$store.dispatch(
        "devices/update",
        {
          id: this.origDevice.id,
          name: this.origDevice.name,
          seoLink: this.origDevice.seoLink,
          previewPicturePath: this.origDevice.previewPicturePath,
          deviceXDeviceOptions: this.device.deviceXDeviceOptions,
          deviceXDeviceGroups: this.currentSelectedDeviceGroups
        }
      );
    },
    async deleteDevice() {
      await this.$store.dispatch("devices/delete", this.device.id);
    },
    async deleteDeviceImage(key) {
      let imagePath = this.deviceImages[key];
      let fileName = btoa(this.extractFileName(imagePath));
      let result = null;

      if (imagePath.match(/^\/uploads\//)) {
        result = await this.$store.dispatch("devices/deleteUploadImage", fileName);
      } else {
        result = await this.$store.dispatch("devices/deleteDeviceImage", {fileName: fileName, id: this.device.id});
      }

      if (result !== null) {
        this.$delete(this.deviceImages, key);
      }
    },
    setImageAsPreview(key) {
      this.origDevice.previewPicturePath = this.extractFileName(this.deviceImages[key]);
      this.previewPictureKey = key;
    },
    reset() {
      this.currentStatus = STATUS_INITIAL;
      this.uploadedFiles = [];
      this.uploadError = null;
    },
    save(formData) {
      this.currentStatus = STATUS_SAVING;
      let me = this;

      upload(formData)
        .then(function() {
          me.$store.dispatch("devices/loadImages", me.origDevice.id);
          me.currentStatus = STATUS_SUCCESS;
        })
        .catch(err => {
          me.uploadError = err.response;
          me.currentStatus = STATUS_FAILED;
        });
    },
    filesChange(fieldName, fileList) {
      const formData = new FormData();

      if (!fileList.length) return;

      Array
        .from(Array(fileList.length).keys())
        .forEach(position => formData.append(fieldName+"[]", fileList[position], fileList[position].name));

      this.save(formData);
    },
    extractFileName(fileName) {
      return fileName.split('/').reverse()[0];
    },
    saveDeviceGroupSelection(deviceGroup)
    {
      if (null === deviceGroup) {
        this.currentSelectedDeviceGroups = [];
        return;
      }

      if (this.currentSelectedDeviceGroups[0]
        && this.currentSelectedDeviceGroups[0].deviceGroup.id === deviceGroup.id
      ) {
        return;
      }

      if (this.currentSelectedDeviceGroups[0]) {
        this.currentSelectedDeviceGroups[0]['deviceGroup'] = deviceGroup;
        return;
      }

      this.currentSelectedDeviceGroups = [];
      let deviceXDeviceGroup = {
        'deviceGroup': deviceGroup
      };

      if (this.isValidId(this.origDevice.id)) {
        deviceXDeviceGroup['device'] = '/api/devices/'+this.origDevice.id
      }

      this.currentSelectedDeviceGroups.push(deviceXDeviceGroup);
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device'));
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
