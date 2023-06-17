//import { render, fireEvent, screen } from '@testing-library/vue';
import { mount } from '@vue/test-utils';
import Device from '../../src/components/Device.vue';
//const Device = require('../../src/components/Device.vue');
//import sinon from 'sinon';

test('Test component is loaded', async() => {
//  render(Device);
  const wrapper = mount(Device);

  await wrapper.trigger('click');

});
