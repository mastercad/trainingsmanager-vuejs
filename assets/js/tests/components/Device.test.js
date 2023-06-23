import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import Device from '../../components/Device.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let actions, store;

beforeEach(() => {
  actions = [];
  actions['devices/loadImages'] = jest.fn();

  store = new Vuex.Store({
    actions
  });
});

describe('Device.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      Device, {
        $store: {store},
        propsData: {
          device: {
            id: 1234,
            name: "Test Device"
          },
          possibleDeviceOptions: []
        },
        store,
        localVue
    });
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#device_name').text()).toBe('Test Device');
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
      1234
    );
  });
});
