import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import Exercise from '../../components/Exercise.vue';

const localVue = createLocalVue();
localVue.use(Vuex);

let actions, store, storeParams;

beforeEach(() => {
  actions = {};
  actions['exercises/loadImages'] = jest.fn();

  store = new Vuex.Store({
    actions
  });
});

describe('Exercise.vue', () => {
  let wrapper;
  beforeEach(() => {
    wrapper = mount(
      Exercise, {
        $store: {store},
        propsData: {
          exercise: {
            id: 1234,
            name: "Test Exercise"
          },
          possibleExerciseOptions: []
        },
        store,
        localVue
      }
    );

    storeParams = {
      commit: store.commit,
      dispatch: store.dispatch,
      getters: store.getters,
      rootGetters: store.getters,
      rootState: store.state,
      state: store.state
    };
  });

  test('Test component is loaded', () => {
    expect(wrapper.find('#exercise_name').text()).toBe('Test Exercise');
    expect(actions['exercises/loadImages']).toBeCalledTimes(1);
    expect(actions['exercises/loadImages']).toHaveBeenCalledWith(
      storeParams,
      1234
    );
  });
});
