import Vue from 'vue';
import Vuex from 'vuex';
import { mount, createLocalVue } from '@vue/test-utils';
import OptionsForEdit from '../../components/OptionsForEdit.vue';
import { BootstrapVue, IconsPlugin, DropdownPlugin } from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(DropdownPlugin);

const localVue = createLocalVue();
localVue.use(Vuex);

describe('OptionsForEdit.vue', () => {
  let wrapper;

  test('Test component is loaded without existing or possible options', () => {
    prepareWrapper('exercise', 1234, 'Exercise Option Test Description', [], []);

    expect(wrapper.findComponent('#exercise_options').exists()).toBeTruthy();
    expect(wrapper.findAllComponents('.exercise_option')).toHaveLength(0);
  });

  test('Test component is loaded without existing but with possible options', () => {
    const possibleOptions = [
      {
        id:7,
        name: "Sitzposition",
        defaultValue: "1",
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created:"2023-08-26T12:02:09+00:00"
      },
      {
        id: 8,
        name: "Wiederholungen",
        defaultValue: "1|2|3",
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created: "2023-08-26T12:04:07+00:00"
      }
    ];
    const currentOptions = [];

    prepareWrapper('exercise', 33, 'Exercise Option Test Description', possibleOptions, currentOptions);

    expect(wrapper.findComponent('#exercise_options').exists()).toBeTruthy();
    expect(wrapper.findAllComponents('.exercise_option')).toHaveLength(2);

    const option1 = wrapper.findComponent('#exercise_option_33_7');
    expect(option1.element.tagName === 'INPUT').toBeTruthy();
    expect(option1.attributes().placeholder).toBe("1");
    expect(option1.element.value).toBe("");

    const option2 = wrapper.findComponent('#exercise_option_33_8');
    const dropDownCollection = option2.findComponent('ul.dropdown-menu').findAllComponents('li');
    expect(dropDownCollection).toHaveLength(4);

    const optionPartNone = dropDownCollection.at(0);
    expect(optionPartNone.element.tagName === 'LI').toBeTruthy();
    const optionPartNoneLink = optionPartNone.findComponent('#exercise_option_null');
    expect(optionPartNoneLink.text()).toBe('None');
    expect(optionPartNoneLink.attributes('class')).toContain('active');

    const optionPart1 = dropDownCollection.at(1);
    expect(optionPart1.element.tagName === 'LI').toBeTruthy();
    const optionPart1Link = optionPart1.findComponent('#exercise_option_33_8_0');
    expect(optionPart1Link.text()).toBe('1');
    expect(optionPart1Link.attributes('class')).not.toContain('active');

    const optionPart2 = dropDownCollection.at(2);
    expect(optionPart2.element.tagName === 'LI').toBeTruthy();
    const optionPart2Link = optionPart2.findComponent('#exercise_option_33_8_1');
    expect(optionPart2Link.text()).toBe('2');
    expect(optionPart2Link.attributes('class')).not.toContain('active');

    const optionPart3 = dropDownCollection.at(3);
    expect(optionPart3.element.tagName === 'LI').toBeTruthy();
    const optionPart3Link = optionPart3.findComponent('#exercise_option_33_8_2');
    expect(optionPart3Link.text()).toBe('3');
    expect(optionPart3Link.attributes('class')).not.toContain('active');
  });

  test('Test component is loaded with existing and with possible options', () => {
    const possibleOptions = [
      {
        id:7,
        name: "Sitzposition",
        defaultValue: "1",
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created:"2023-08-26T12:02:09+00:00"
      },
      {
        id: 8,
        name: "Wiederholungen",
        defaultValue: "1|2|3",
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created: "2023-08-26T12:04:07+00:00"
      }
    ];

    const currentOptions = [
      {
        id: 16,
        exercise: "/api/exercises/33/images",
        exerciseOption:
        {
          id: 7,
          name: "Sitzposition",
          defaultValue: "1",
          creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
          created: "2023-08-26T12:02:09+00:00"
        },
        exerciseOptionValue: 5,
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created: "2023-08-27T19:42:32+00:00"
      },
      {
        id: 17,
        exercise: "/api/exercises/33/images",
        exerciseOption:
        {
          id: 8,
          name: "Wiederholungen",
          defaultValue: "1|2|3",
          creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
          created: "2023-08-26T12:04:07+00:00"
        },
        exerciseOptionValue: 2,
        creator: "/api/users/f0fbfe14-7a4b-4603-aa57-98c7808d843a",
        created: "2023-08-27T19:42:32+00:00"
      }
    ];
    prepareWrapper('exercise', 33, 'Exercise Option Test Description', possibleOptions, currentOptions);

    expect(wrapper.findComponent('#exercise_options').exists()).toBeTruthy();
    expect(wrapper.findAllComponents('.exercise_option')).toHaveLength(2);

    const option1 = wrapper.findComponent('#exercise_option_33_7');
    expect(option1.element.tagName === 'INPUT').toBeTruthy();
    expect(option1.attributes().placeholder).toBe("1");
    expect(option1.element.value).toBe("5");

    const option2 = wrapper.findComponent('#exercise_option_33_8');
    const dropDownCollection = option2.findComponent('ul.dropdown-menu').findAllComponents('li');
    expect(dropDownCollection).toHaveLength(4);

    const optionPartNone = dropDownCollection.at(0);
    expect(optionPartNone.element.tagName === 'LI').toBeTruthy();
    const optionPartNoneLink = optionPartNone.findComponent('#exercise_option_null');
    expect(optionPartNoneLink.text()).toBe('None');
    expect(optionPartNoneLink.attributes('class')).not.toContain('active');

    const optionPart1 = dropDownCollection.at(1);
    expect(optionPart1.element.tagName === 'LI').toBeTruthy();
    const optionPart1Link = optionPart1.findComponent('#exercise_option_33_8_0');
    expect(optionPart1Link.text()).toBe('1');
    expect(optionPart1Link.attributes('class')).not.toContain('active');

    const optionPart2 = dropDownCollection.at(2);
    expect(optionPart2.element.tagName === 'LI').toBeTruthy();
    const optionPart2Link = optionPart2.findComponent('#exercise_option_33_8_1');
    expect(optionPart2Link.text()).toBe('2');
    expect(optionPart2Link.attributes('class')).toContain('active');

    const optionPart3 = dropDownCollection.at(3);
    expect(optionPart3.element.tagName === 'LI').toBeTruthy();
    const optionPart3Link = optionPart3.findComponent('#exercise_option_33_8_2');
    expect(optionPart3Link.text()).toBe('3');
    expect(optionPart3Link.attributes('class')).not.toContain('active');
  });

  function prepareWrapper(type, identifier, description, possibleOptions, currentOptions, additionalOptions)
  {
    wrapper = mount(
      OptionsForEdit, {
        propsData: {
          type: type,
          identifier: identifier,
          description: description,
          possibleOptions: possibleOptions,
          currentOptions: currentOptions,
          additionalOptions: additionalOptions
        },
        localVue
      }
    );
  }
});
