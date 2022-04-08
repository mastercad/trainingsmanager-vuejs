<template>
  <div>
    <div class="row">
      <h1>Devices</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDevice({
            id: null,
            name: '',
            seoLink: '',
            previewPicturePath: ''
          })"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-4">
        <div style="padding-right: 15px; position: absolute; overflow-y: auto; height: 100%; max-height: 100%;">
          <ul>
            <li
              v-for="currentDevice in devices"
              :key="currentDevice.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentDevice)"
              @click="loadDevice($event, currentDevice)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: device && device.id === currentDevice.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentDevice.name }}
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-8">
        <component
          :is="currentPanel"
          v-if="device"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="device.id"

          :key="device.id"
          :ref="'devicePanel'"

          :name="device.name"
          :seo-link="device.seoLink"
          :preview-picture-path="device.previewPicturePath"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import Device from "../components/Device";
import DeviceEdit from "../components/DeviceEdit";

export default {
  name: "DevicesView",
  components: {
    Device,
    DeviceEdit
  },
  data() {
    return {
      device: null,
      result: [],
      delay: 200,
      clicks: 0,
      timer: null,
      currentPanel: '',
      leftPanelVisibility: false,
      options: [
        {
          name: 'Edit',
          slug: 'edit'
        },
        {
          name: 'Show',
          slug: 'show'
        },
        {
          type: 'divider'
        },
        {
          name: 'Delete',
          slug: 'delete'
        }
      ]
    };
  },
  computed: {
    isLoading() {
      return this.$store.getters["devices/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["devices/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["devices/hasError"];
    },
    error() {
      return this.$store.getters["devices/error"];
    },
    hasDevices() {
      return this.$store.getters["devices/hasDevices"];
    },
    devices() {
      return this.$store.getters["devices/devices"];
    }
  },
  created() {
    this.$store.dispatch("devices/findAll");
  },
  methods: {
    async loadDevice(event, device) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showDevice(device);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editDevice(device);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showDevice(device) {
      this.device = device;
      this.currentPanel = 'Device';
      this.leftPanelVisibility=true;
    },
    async editDevice(device) {
      this.device = device;
      this.currentPanel = 'DeviceEdit';
      this.leftPanelVisibility = true;
    },
    async handleClick(event, device) {
      this.$refs.vueSimpleContextMenu.showMenu(event, device)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showDevice(event.item);
      } else if ('edit' === event.option.slug) {
        this.editDevice(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteDevice();
      }
    }
  }
};
</script>

<style>
  .col.list-group-item {
    cursor: pointer;
  }
</style>
