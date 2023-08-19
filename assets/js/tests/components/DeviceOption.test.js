import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import DeviceOption from '../../components/DeviceOption.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let store;

describe('DeviceOption.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
        DeviceOption, {
        $store: {store},
        propsData: {
          deviceOption: {
            id: 1234,
            name: "Test Device Option",
            defaultValue: 4321
          }
        },
        store,
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#device_option_name').text()).toBe('Test Device Option');
    expect(wrapper.find('#device_option_default_value').text()).toBe('4321');
  });
});
