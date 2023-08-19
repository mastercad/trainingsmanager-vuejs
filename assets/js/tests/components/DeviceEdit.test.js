import Vuex from 'vuex';
import { BootstrapVue, IconsPlugin, DropdownPlugin } from 'bootstrap-vue';

import { mount, createLocalVue } from '@vue/test-utils';
import DeviceEdit from '../../components/DeviceEdit.vue';

const localVue = createLocalVue();
localVue.use(Vuex);
localVue.use(BootstrapVue);
localVue.use(IconsPlugin);
localVue.use(DropdownPlugin);

let actions, store, storeParams;

beforeEach(() => {
  actions = {};
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

  test('Try create new entry without required entries => not possible', () => {
    mountView('device_1687282039594');

    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      storeParams,
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
    mountView('device_1687282039594');

    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      storeParams,
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
      storeParams,
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

  test('Update entry', async () => {
    mountView('12312');

    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      storeParams,
      '12312'
    );

    let nameInput = wrapper.find('#device_name');
    await nameInput.setValue('Name Test Device');

    let buttonSave = wrapper.find('#button_save');
    expect(buttonSave.text()).toBe('Update');
    expect(buttonSave.exists()).toBeTruthy();
    expect(buttonSave.attributes().disabled).toBe(undefined);

    await buttonSave.trigger('click');

    let buttonDelete = wrapper.find('#button_delete');
    expect(buttonDelete.exists()).toBeTruthy();

    expect(actions['devices/update']).toBeCalledTimes(1);
    expect(actions['devices/update']).toBeCalledWith(
      storeParams,
      {
        id: '12312',
        name: 'Name Test Device',
        seoLink: '',
        previewPicturePath: '',
        deviceXDeviceOptions: [],
        deviceXDeviceGroups: []
      }
    );
    expect(actions['devices/create']).toBeCalledTimes(0);
    expect(actions['devices/delete']).toBeCalledTimes(0);
  });

  test('Delete entry', async () => {
    mountView('12312');

    expect(wrapper.find('#device_name').text()).toBe('');
    expect(actions['deviceGroups/findAll']).toBeCalledTimes(1);

    expect(actions['devices/loadImages']).toBeCalledTimes(1);
    expect(actions['devices/loadImages']).toHaveBeenCalledWith(
      storeParams,
      '12312'
    );

    let nameInput = wrapper.find('#device_name');
    await nameInput.setValue('Name Test Device');

    let buttonSave = wrapper.find('#button_save');
    expect(buttonSave.text()).toBe('Update');
    expect(buttonSave.exists()).toBeTruthy();
    expect(buttonSave.attributes().disabled).toBe(undefined);

    let buttonDelete = wrapper.find('#button_delete');
    expect(buttonDelete.exists()).toBeTruthy();

    await buttonDelete.trigger('click');

    expect(actions['devices/delete']).toBeCalledTimes(1);
    expect(actions['devices/delete']).toBeCalledWith(
      storeParams,
      '12312'
    );
    expect(actions['devices/create']).toBeCalledTimes(0);
    expect(actions['devices/update']).toBeCalledTimes(0);
  });

  function mountView(deviceId){
    wrapper = mount(
      DeviceEdit, {
        $store: {store},
        propsData: {
          device: {
            id: deviceId,
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

    storeParams = {
      commit: store.commit,
      dispatch: store.dispatch,
      getters: store.getters,
      rootGetters: store.getters,
      rootState: store.state,
      state: store.state
    };
  }
});
