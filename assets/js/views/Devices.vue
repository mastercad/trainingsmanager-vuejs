<template>
  <div>
    <div class="row mt-2">
      <h1>Devices</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDevice(generateNewDevice())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item
            v-for="currentDevice in devices"
            :key="currentDevice.id"
            href="#"
            class="list-group-item"
            :active="device && device.id === currentDevice.id"
            unselectable="on"
            onselectstart="return false;"
            @contextmenu.prevent.stop="handleClick($event, currentDevice)"
            @click="loadDevice($event, currentDevice)"
          >
            {{ currentDevice.name }}
          </b-list-group-item>
        </b-list-group>
      </div>

      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="device"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="device.id"

          :key="device.id"
          :ref="'devicePanel'"

          :device="device"
          :possible-device-options="deviceOptions"
        />
      </div>
    </div>

    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->
    <vue-simple-context-menu
      :id="'devicesContextMenu'"
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import Device from "../components/Device";
import DeviceEdit from "../components/DeviceEdit";
import IdGenerator from "../shared/idGenerator";

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
      return this.$store.getters["devices/isLoading"] || this.$store.getters["deviceOptions/isLoading"];
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
    },
    deviceOptions() {
      return this.$store.getters["deviceOptions/deviceOptions"];
    },
    findOldRegistered() {
      return this.$store.getters["devices/findOldRegistered"];
    }
  },
  created() {
    Promise.all([
      this.$store.dispatch("deviceOptions/findAll"),
      this.$store.dispatch("devices/findAll")
    ]).finally(() => {
      this.loading = false
    })
  },
  methods: {
    async loadDevice(event, device) {
      this.clicks++;
      if (1 === this.clicks) {
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
    async deleteDevice(device) {
      if (this.isGenericId(device.id)) {
        await this.$store.dispatch("devices/unregister", device.id);
        this.leftPanelVisibility = false;
      } else {
        await this.$store.dispatch("devices/delete", device.id);
        this.leftPanelVisibility = false;
      }
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
        this.deleteDevice(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('device')
    },
    generateNewDevice() {
      let device = this.$store.getters["devices/findOldRegistered"];

      if (!device) {
        device = {
          id: this.generateIdentifier(),
          name: '',
          seoLink: '',
          previewPicturePath: '',
          deviceXDeviceOptions: [],
          deviceXDeviceGroups: []
        };

        this.$store.dispatch("devices/register", device);
      }

      return device;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device'));
    }
  }
};
</script>

<style>
  .col.list-group-item {
    cursor: pointer;
  }
</style>
