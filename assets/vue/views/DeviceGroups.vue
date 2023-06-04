<template>
  <div>
    <div class="row">
      <h1>Device Options</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDeviceGroup({
            id: null,
            name: '',
            seoLink: ''
          })"
        >
          Add
        </button>
      </div>
    </div>
    <div class="row gx-0">
      <div
        class="col-3"
        style="background-color: lime;"
      >
        <div style="padding-right: 15px; position: absolute; overflow-y: auto; height: 100%; max-height: 100%;">
          <ul>
            <li
              v-for="currentDeviceGroup in deviceGroups"
              :key="currentDeviceGroup.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentDeviceGroup)"
              @click="loadDeviceGroup($event, currentDeviceGroup)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: deviceGroup && deviceGroup.id === currentDeviceGroup.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentDeviceGroup.name }}
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div
        class="col-9"
        style="background-color: lightblue;"
      >
        <component
          :is="currentPanel"
          v-if="deviceGroup"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="deviceGroup.id"

          :key="deviceGroup.id"
          :ref="'deviceGroupPanel'"

          :name="deviceGroup.name"
          :seo-link="deviceGroup.seoLink"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'deviceGroupsContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import DeviceGroup from "../components/DeviceGroup.vue";
import DeviceGroupEdit from "../components/DeviceGroupEdit.vue";

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
        this.deleteDeviceGroup();
      }
    }
  }
};
</script>
