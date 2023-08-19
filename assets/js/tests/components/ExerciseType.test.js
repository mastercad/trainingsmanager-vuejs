import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import ExerciseType from '../../components/ExerciseType.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let store;

describe('ExerciseType.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      ExerciseType, {
        $store: {store},
        propsData: {
          exerciseType: {
            id: 1234,
            name: "Test Exercise Type"
          }
        },
        store,
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#exercise_type_name').text()).toBe('Test Exercise Type');
  });
});
