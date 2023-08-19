import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import MuscleGroup from '../../components/MuscleGroup.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

describe('Muscle.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      MuscleGroup, {
        propsData: {
          muscleGroup: {
            id: 1234,
            name: "Test Muscle Group",
            seoLink: "test_muscle_group_seo_link"
          }
        },
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#muscle_group_name').text()).toBe('Test Muscle Group');
    expect(wrapper.find('#muscle_group_seo_link').text()).toBe('test_muscle_group_seo_link');
  });
});
