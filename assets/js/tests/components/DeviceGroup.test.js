import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import DeviceGroup from '../../components/DeviceGroup.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let store;

describe('DeviceGroup.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      DeviceGroup, {
        $store: {store},
        propsData: {
          deviceGroup: {
            id: 1234,
            name: "Test Device Group"
          }
        },
        store,
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#device_group_name').text()).toBe('Test Device Group');
  });
});
