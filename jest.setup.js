import lodash from 'lodash';
import '@testing-library/jest-dom';
import { TextEncoder } from 'util';

global.TextEncoder = TextEncoder;
global._ = lodash;
