<template>
  <div>
    <div class="row mt-2">
      <h1>Device Groups</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDeviceGroup(generateNewDeviceGroup())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item
            v-for="currentDeviceGroup in deviceGroups"
            :key="currentDeviceGroup.id"
            href="#"
            class="list-group-item"
            :active="deviceGroup && deviceGroup.id === currentDeviceGroup.id"
            unselectable="on"
            onselectstart="return false;"
            @contextmenu.prevent.stop="handleClick($event, currentDeviceGroup)"
            @click="loadDeviceGroup($event, currentDeviceGroup)"
          >
            {{ currentDeviceGroup.name }}
          </b-list-group-item>
        </b-list-group>
      </div>
      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="deviceGroup"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="deviceGroup.id"

          :key="deviceGroup.id"
          :ref="'deviceGroupPanel'"

          :device-group="deviceGroup"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :id="'deviceGroupsContextMenu'"
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import DeviceGroup from "../components/DeviceGroup.vue";
import DeviceGroupEdit from "../components/DeviceGroupEdit.vue";
import IdGenerator from "../shared/idGenerator";

export default {
  name: "DeviceGroupsView",
  components: {
    DeviceGroup,
    DeviceGroupEdit
  },
  data() {
    return {
      deviceGroup: null,
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
      return this.$store.getters["deviceGroups/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["deviceGroups/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["deviceGroups/hasError"];
    },
    error() {
      return this.$store.getters["deviceGroups/error"];
    },
    hasDeviceGroup() {
      return this.$store.getters["deviceGroups/hasDeviceGroup"];
    },
    deviceGroups() {
      return this.$store.getters["deviceGroups/deviceGroups"];
    },
    findOldRegistered() {
      return this.$store.getters["deviceGroups/findOldRegistered"];
    }
  },
  created() {
    this.$store.dispatch("deviceGroups/findAll");
  },
  methods: {
    async loadDeviceGroup(event, deviceGroup) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showDeviceGroup(deviceGroup);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editDeviceGroup(deviceGroup);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showDeviceGroup(deviceGroup) {
      this.deviceGroup = deviceGroup;
      this.currentPanel = 'DeviceGroup';
      this.leftPanelVisibility=true;
    },
    async editDeviceGroup(deviceGroup) {
      this.deviceGroup = deviceGroup;
      this.currentPanel = 'DeviceGroupEdit';
      this.leftPanelVisibility = true;
    },
    async deleteDeviceGroup(deviceGroup) {
      if (this.isGenericId(deviceGroup.id)) {
        await this.$store.dispatch("deviceGroups/unregister", deviceGroup.id);
        this.leftPanelVisibility = false;
      } else {
        await this.$store.dispatch("deviceGroups/delete", deviceGroup.id);
        this.leftPanelVisibility = false;
      }
    },
    async handleClick(event, deviceGroup) {
      this.$refs.vueSimpleContextMenu.showMenu(event, deviceGroup)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showDeviceGroup(event.item);
      } else if ('edit' === event.option.slug) {
        this.editDeviceGroup(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteDeviceGroup(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('device_group_')
    },
    generateNewDeviceGroup() {
      let deviceGroup = this.$store.getters["deviceGroups/findOldRegistered"];

      if (!deviceGroup) {
        deviceGroup = {
          id: this.generateIdentifier(),
          name: '',
          seoLink: ''
        };

        this.$store.dispatch("deviceGroups/register", deviceGroup);
      }

      return deviceGroup;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device_group_'));
    }
  }
};
</script>
