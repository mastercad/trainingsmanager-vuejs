import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import Muscle from '../../components/Muscle.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

describe('Muscle.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      Muscle, {
        propsData: {
          muscle: {
            id: 1234,
            name: "Test Muscle",
            seoLink: "test_muscle_seo_link"
          }
        },
        localVue
      }
    );
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#muscle_name').text()).toBe('Test Muscle');
    expect(wrapper.find('#muscle_seo_link').text()).toBe('test_muscle_seo_link');
  });
});
