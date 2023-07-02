import Vuex from 'vuex';
import { BootstrapVue, IconsPlugin, DropdownPlugin } from 'bootstrap-vue';

import { mount, createLocalVue } from '@vue/test-utils';
import DeviceEdit from '../../components/DeviceEdit.vue';

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(BootstrapVue);
localVue.use(IconsPlugin);
localVue.use(DropdownPlugin);

let actions, store;

beforeEach(() => {
  actions = [];
  actions['devices/loadImages'] = jest.fn();
  actions['devices/create'] = jest.fn();
  actions['devices/update'] = jest.fn();
  actions['devices/delete'] = jest.fn();
  actions['deviceGroups/findAll'] = jest.fn();
  actions['deviceGroups/deviceGroups'] = jest.fn().mockReturnValue([]);

  store = new Vuex.Store({
    actions
  });
});

describe('DeviceEdit.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      DeviceEdit, {
        $store: {store},
        propsData: {
          device: {
            id: 'device_1687282039594',
            name: '',
            seoLink: '',
            previewPicturePath: '',
            deviceXDeviceOptions: [],
            deviceXDeviceGroups: []
          },
          possibleDeviceOptions: []
        },
        store,
        localVue
      });
  });

  test('Create new entry without required entries', () => {
    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      {
        commit: store.commit,
        dispatch: store.dispatch,
        getters: store.getters,
        rootGetters: store.getters,
        rootState: store.state,
        state: store.state
      },
      'device_1687282039594'
    );

    let button = wrapper.find('#button_save');
    expect(button.text()).toBe('Create');
    button.trigger('click');

    expect(button.attributes().disabled).toBe('disabled');

    expect(actions['devices/create']).toBeCalledTimes(0);
    expect(actions['devices/update']).toBeCalledTimes(0);
    expect(actions['devices/delete']).toBeCalledTimes(0);
  });

  test('Create new entry with minimal required entries', async () => {
    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      {
        commit: store.commit,
        dispatch: store.dispatch,
        getters: store.getters,
        rootGetters: store.getters,
        rootState: store.state,
        state: store.state
      },
      'device_1687282039594'
    );

    let nameInput = wrapper.find('#device_name');
    await nameInput.setValue('Name New Test Device');

    let buttonSave = wrapper.find('#button_save');
    expect(buttonSave.text()).toBe('Create');
    expect(buttonSave.exists()).toBeTruthy();
    expect(buttonSave.attributes().disabled).toBe(undefined);

    await buttonSave.trigger('click');

    let buttonDelete = wrapper.find('#button_delete');
    expect(buttonDelete.exists()).toBeFalsy();

    expect(actions['devices/create']).toBeCalledTimes(1);
    expect(actions['devices/create']).toBeCalledWith(
      {
        commit: store.commit,
        dispatch: store.dispatch,
        getters: store.getters,
        rootGetters: store.getters,
        rootState: store.state,
        state: store.state
      },
      {
        id: 'device_1687282039594',
        name: 'Name New Test Device',
        seoLink: '',
        previewPicturePath: '',
        deviceXDeviceOptions: [],
        deviceXDeviceGroups: []
      }
    );
    expect(actions['devices/update']).toBeCalledTimes(0);
    expect(actions['devices/delete']).toBeCalledTimes(0);
  });
});
