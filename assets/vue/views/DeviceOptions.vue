<template>
  <div>
    <div class="row">
      <h1>Device Options</h1>
      <div class="col-3">
        <button
          type="button"
          class="btn btn-primary"
          @click="editDeviceOption({
            id: null,
            name: '',
            defaultValue: ''
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
              v-for="currentDeviceOption in deviceOptions"
              :key="currentDeviceOption.id"
              class="row"
              @contextmenu.prevent.stop="handleClick($event, currentDeviceOption)"
              @click="loadDeviceOption($event, currentDeviceOption)"
            >
              <div
                class="list-group-item list-group-item-action"
                v-bind:class="{ active: deviceOption && deviceOption.id === currentDeviceOption.id}"
                unselectable="on"
                onselectstart="return false;"
              >
                {{ currentDeviceOption.name }}
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
          v-if="deviceOption"
          v-show="leftPanelVisibility && !isPanelLoading"

          :id="deviceOption.id"

          :key="deviceOption.id"
          :ref="'deviceOptionPanel'"

          :name="deviceOption.name"
          :default-value="deviceOption.defaultValue"
        />
      </div>
    </div>
    <!-- Make sure you add the `ref` attribute, as that is what gives you the ability to open the menu. -->

    <vue-simple-context-menu
      :ref="'vueSimpleContextMenu'"
      :element-id="'contextMenu'"
      :id="'deviceOptionsContextMenu'"
      :options="options"
      @option-clicked="optionClicked"
    />
  </div>
</template>

<script>
import DeviceOption from "../components/DeviceOption.vue";
import DeviceOptionEdit from "../components/DeviceOptionEdit.vue";

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
        this.deleteDeviceOption();
      }
    }
  }
};
</script>
