<template>
  <div>
    <div class="row mt-2">
      <h1>Device Options</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDeviceOption(generateNewDeviceOption())"
        >
          Add
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-3">
        <b-list-group>
          <b-list-group-item href="#"
            v-for="currentDeviceOption in deviceOptions"
            :key="currentDeviceOption.id"
            @contextmenu.prevent.stop="handleClick($event, currentDeviceOption)"
            @click="loadDeviceOption($event, currentDeviceOption)"
            class="list-group-item"
            :active="deviceOption && deviceOption.id === currentDeviceOption.id"
            unselectable="on"
            onselectstart="return false;"
          >
            {{ currentDeviceOption.name }}
          </b-list-group-item>
        </b-list-group>
      </div>
      <div class="col-9">
        <component
          :is="currentPanel"
          v-if="deviceOption"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="deviceOption.id"

          :key="deviceOption.id"
          :ref="'deviceOptionPanel'"

          :deviceOption="deviceOption"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'deviceOptionsContextMenu'"
      :options="options"
      @optionClicked="optionClicked"
    />
  </div>
</template>

<script>
import DeviceOption from "../components/DeviceOption.vue";
import DeviceOptionEdit from "../components/DeviceOptionEdit.vue";
import IdGenerator from "../shared/idGenerator";

export default {
  name: "DeviceOptionsView",
  components: {
    DeviceOption,
    DeviceOptionEdit
  },
  data() {
    return {
      deviceOption: null,
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
      return this.$store.getters["deviceOptions/isLoading"];
    },
    isPanelLoading() {
      return this.$store.getters["deviceOptions/isPanelLoading"];
    },
    hasError() {
      return this.$store.getters["deviceOptions/hasError"];
    },
    error() {
      return this.$store.getters["deviceOptions/error"];
    },
    hasDeviceOption() {
      return this.$store.getters["deviceOptions/hasDeviceOption"];
    },
    deviceOptions() {
      return this.$store.getters["deviceOptions/deviceOptions"];
    },
    findOldRegistered() {
      return this.$store.getters["deviceOptions/findOldRegistered"];
    }
  },
  created() {
    this.$store.dispatch("deviceOptions/findAll");
  },
  methods: {
    async loadDeviceOption(event, deviceOption) {
      this.clicks++;
      if (this.clicks === 1) {
        this.timer = setTimeout( () => {
          this.showDeviceOption(deviceOption);
          this.result.push(event.type);
          this.clicks = 0;
          this.result = [];
        }, this.delay);
      } else {
        this.editDeviceOption(deviceOption);
        clearTimeout(this.timer);
        this.result.push('dblclick');
        this.clicks = 0;
        this.result = [];
      }
    },
    async showDeviceOption(deviceOption) {
      this.deviceOption = deviceOption;
      this.currentPanel = 'DeviceOption';
      this.leftPanelVisibility=true;
    },
    async editDeviceOption(deviceOption) {
      this.deviceOption = deviceOption;
      this.currentPanel = 'DeviceOptionEdit';
      this.leftPanelVisibility = true;
    },
    async deleteDeviceOption(deviceOption) {
      if (this.isGenericId(deviceOption.id)) {
        const result = await this.$store.dispatch("deviceOptions/unregister", deviceOption.id);
        this.leftPanelVisibility = false;
      } else {
        const result = await this.$store.dispatch("deviceOptions/delete", deviceOption.id);
        this.leftPanelVisibility = false;
      }
    },
    async handleClick(event, deviceOption) {
      this.$refs.vueSimpleContextMenu.showMenu(event, deviceOption)
    },
    async optionClicked(event) {
      if ('show' === event.option.slug) {
        this.showDeviceOption(event.item);
      } else if ('edit' === event.option.slug) {
        this.editDeviceOption(event.item);
      } else if ('delete' === event.option.slug) {
        this.id = event.item.id;
        this.deleteDeviceOption(event.item);
      }
    },
    generateIdentifier() {
      return IdGenerator.generate('device_option_')
    },
    generateNewDeviceOption() {
      let deviceOption = this.$store.getters["deviceOptions/findOldRegistered"];
      if (!deviceOption) {
        deviceOption = {
          id: this.generateIdentifier(),
          name: '',
          defaultValue: ''
        };

        this.$store.dispatch("deviceOptions/register", deviceOption);
      }

      return deviceOption;
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith('device_option_'));
    }
  }
};
</script>
