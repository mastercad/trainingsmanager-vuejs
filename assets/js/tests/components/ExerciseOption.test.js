import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import ExerciseOption from '../../components/ExerciseOption.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let store;

describe('ExerciseOption.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      ExerciseOption, {
        $store: {store},
        propsData: {
          exerciseOption: {
            id: 1234,
            name: "Test Exercise Option",
            defaultValue: 4321
          }
        },
        store,
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#exercise_option_name').text()).toBe('Test Exercise Option');
    expect(wrapper.find('#exercise_option_default_value').text()).toBe('4321');
  });
});
