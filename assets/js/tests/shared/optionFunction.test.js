const OptionFunctions = require("../../shared/optionFunctions");
import { TextEncoder } from 'util';

global.TextEncoder = TextEncoder;

//import OptionsFunctions from "../shared/optionFunctions.js";

describe("Option function tests", () => {
  test('Split works with empty parameter', () => {
    let result = OptionFunctions.splitOption("");
    expect(result).toMatchObject(['']);
  });

  test('Prepare Option Collection', () => {
    let collection =
      [
        {
          id: 1,
          currentValue: 12
        },
        {
          id: 2,
          currentValue: 14,
          creator: 'test'
        },
        {
          id: 4,
          currentValue: 21,
          updated: null
        }
      ],
      identifier = function(option) {return option.id;},
      valueKey = function(option) {return option.currentValue;};

    let expectedResult = {
      '1': { id: 1, value: 12, origEntry: { id: 1, currentValue: 12 } },
      '2': {
        id: 2,
        value: 14,
        origEntry: { id: 2, currentValue: 14, creator: 'test' }
      },
      '4': {
        id: 4,
        value: 21,
        origEntry: { id: 4, currentValue: 21, updated: null }
      }
    };

    let resultCollection = OptionFunctions.prepareOptionCollection(collection, identifier, valueKey);
    for(const [key, value] of Object.entries(resultCollection)) {
      expect(resultCollection[key]).toEqual(expectedResult[key]);
    }
    expect(resultCollection).toMatchObject(expectedResult);
    expect(OptionFunctions.isIterable(resultCollection)).toBeTruthy();
  });

  test('check if string is multipart without |', () => {
    let result = OptionFunctions.isMultipartOption('');
    expect(result).toBeFalsy();
  });

  test('check if integer is multipart', () => {
    let result = OptionFunctions.isMultipartOption('');
    expect(result).toBeFalsy();
  });

  test('check if string is multipart with |', () => {
    let result = OptionFunctions.isMultipartOption('test|test1');
    expect(result).toBeTruthy();
  });

  test('generate hash by params', () => {
    OptionFunctions.identifier = 1;
    let result = OptionFunctions.generateOptionPartKey(2, 4);
    expect(result).toBe("1_2_4");
  });

  test('check if null is iterable', () => {
    expect(OptionFunctions.isIterable(null)).toBeFalsy();
  });

  test('check if normal object is iterable', () => {
    expect(OptionFunctions.isIterable({id: 10, name: 'sadas'})).toBeTruthy();
  });

  test('check expected object is iterable', () => {
    let possibleOptions = {
      1: {
        id: 1,
        value: 123
      },
      2: {
        id: 2,
        value: 213
      },
      41: {
        id: 41,
        value: 125
      }
    };

    expect(OptionFunctions.isIterable(possibleOptions)).toBeTruthy();
  });

  test('check if iterable object is iterable', () => {
    expect(OptionFunctions.isIterable(new Map)).toBeTruthy();
  });

  test('check if array is iterable', () => {
    expect(OptionFunctions.isIterable([{id: 1, name: 'test'}])).toBeTruthy();
  });

  test('investigate value without any options', () => {
    let result = OptionFunctions.investigateCurrentOptionValue(2);
    expect(result).toBeNull();
  });

  test('investigate value with single options collection', () => {
    let possibleOptions = {
      1: {
        id: 1,
        value: 123
      },
      2: {
        id: 2,
        value: 213
      },
      41: {
        id: 41,
        value: 125
      }
    };

    let result = OptionFunctions.investigateCurrentOptionValue(2, true, possibleOptions);
    expect(result).toBe(213);
  });

  test('investigate value with single options collection but value is multipart and should ignored', () => {
    let possibleOptions = {
      1: {
        id: 1,
        value: 123
      },
      2: {
        id: 2,
        value: 'test|test1|test2'
      },
      41: {
        id: 41,
        value: 125
      }
    };

    let result = OptionFunctions.investigateCurrentOptionValue(2, false, possibleOptions);
    expect(result).toBeNull();
  });

  test('investigate value with single options collection with multipart value', () => {
    let possibleOptions = {
      1: {
        id: 1,
        value: 123
      },
      2: {
        id: 2,
        value: 'test|test1|test2'
      },
      41: {
        id: 41,
        value: 125
      }
    };

    let result = OptionFunctions.investigateCurrentOptionValue(2, true, possibleOptions);
    expect(result).toEqual('test|test1|test2');
  });

  test('investigate value with single options collection but key not exist', () => {
    let possibleOptions = {
      1: {
        id: 1,
        value: 123
      },
      2: {
        id: 2,
        value: 213
      },
      41: {
        id: 41,
        value: 125
      }
    };

    let result = OptionFunctions.investigateCurrentOptionValue(12, true, possibleOptions);
    expect(result).toBeNull();
  });

  test('Generate current options without possible options and previous selection', () => {
    let optionId = 121;
    let possibleOptions = null;
    let result = OptionFunctions.generateCurrentOptions(optionId, true, possibleOptions);

    expect(result).toMatchObject([]);
  });

  test('Generate current options with possible options and without previous selection', () => {
    let optionId = 312;
    let possibleOptions = {
      1: {
        id: 1,
        value: 123,
        name: 'Test Option 1'
      },
      2: {
        id: 2,
        value: 213,
        name: 'Test Option 2'
      },
      41: {
        id: 41,
        value: 125,
        name: 'Test Option 41'
      }
    };
    let expectedResult =
    [
      {
        bindKey: "312_1_null",
        isMultipartOption: false,
        key: "312_1",
        name: 'Test Option 1',
        origOption: {
          id: 1,
          value: 123
        },
        placeholder: 123,
        value: null,
      },
      {
        bindKey: "312_2_null",
        isMultipartOption: false,
        key: "312_2",
        name: 'Test Option 2',
        origOption: {
          id: 2,
          value: 213
        },
        placeholder: 213,
        value: null,
      },
      {
        bindKey: "312_41_null",
        isMultipartOption: false,
        key: "312_41",
        name: 'Test Option 41',
        origOption: {
          id: 41,
          value: 125
        },
        placeholder: 125,
        value: null,
      },
    ];
    let result = OptionFunctions.generateCurrentOptions(optionId, true, possibleOptions);

    expect(result).toMatchObject(expectedResult);
  });

  test('Generate current options with possible options, multipart entry and without previous selection, with named options', () => {
    let optionId = 312;
    let possibleOptions = {
      1: {
        id: 1,
        value: 123,
        name: 'Test Option 1'
      },
      2: {
        id: 2,
        value: 'test|test1|test2',
        name: 'Test Option 2'
      },
      41: {
        id: 41,
        value: 125,
        name: 'Test Option 41'
      }
    };
    let expectedResult =
    [
      {
        bindKey: "312_1_null",
        isMultipartOption: false,
        key: "312_1",
        name: 'Test Option 1',
        origOption: {
          id: 1,
          value: 123
        },
        placeholder: 123,
        value: null,
      },
      {
        bindKey: "312_2_null",
        isMultipartOption: true,
        key: "312_2",
        name: 'Test Option 2',
        origOption: {
          id: 2,
          value: 'test|test1|test2'
        },
        parts: [
          {
            isActive: false,
            key: "312_2_0"
          },
          {
            isActive: false,
            key: "312_2_1"
          },
          {
            isActive: false,
            key: "312_2_2"
          },
        ]
      },
      {
        bindKey: "312_41_null",
        isMultipartOption: false,
        key: "312_41",
        name: 'Test Option 41',
        origOption: {
          id: 41,
          value: 125
        },
        placeholder: 125,
        value: null,
      },
    ];
    let result = OptionFunctions.generateCurrentOptions(optionId, true, possibleOptions);

    expect(result).toMatchObject(expectedResult);
  });

  test('Generate current options with possible options, multipart entry and single previous selection collection in single value, with named options', () => {
    let optionId = 312;
    let possibleOptions = {
      1: {
        id: 1,
        value: 123,
        name: 'Test Option 1'
      },
      2: {
        id: 2,
        value: 'test|test1|test2',
        name: 'Test Option 2'
      },
      41: {
        id: 41,
        value: 125,
        name: 'Test Option 41'
      }
    };
    let firstSelectionCollection = {
      1: {
        id: 1,
        value:"test1"
      }
    };
    let expectedResult =
    [
      {
        bindKey: "312_1_test1",
        isMultipartOption: false,
        key: "312_1",
        name: 'Test Option 1',
        origOption: {
          id: 1,
          value: 123
        },
        placeholder: 123,
        value: "test1"
      },
      {
        bindKey: "312_2_null",
        isMultipartOption: true,
        key: "312_2",
        name: 'Test Option 2',
        origOption: {
          id: 2,
          value: 'test|test1|test2'
        },
        parts: [
          {
            isActive: false,
            key: "312_2_0"
          },
          {
            isActive: false,
            key: "312_2_1"
          },
          {
            isActive: false,
            key: "312_2_2"
          },
        ],
        value: null
      },
      {
        bindKey: "312_41_null",
        isMultipartOption: false,
        key: "312_41",
        name: 'Test Option 41',
        origOption: {
          id: 41,
          value: 125
        },
        placeholder: 125,
        value: null
      },
    ];
    let result = OptionFunctions.generateCurrentOptions(optionId, true, possibleOptions, firstSelectionCollection);

    expect(result).toMatchObject(expectedResult);
  });

  test('Generate current options with possible options, multipart entry and multiple previous selection collections and multiple values', () => {
    let optionId = 312;
    let possibleOptions = {
      1: {
        id: 1,
        value: 123,
        name: 'Test Option 1'
      },
      2: {
        id: 2,
        value: 'test|test1|test2',
        name: 'Test Option 2'
      },
      41: {
        id: 41,
        value: 125,
        name: 'Test Option 41'
      }
    };
    let firstSelectionCollection = {
      2: {
        id: 2,
        value: 'test2'
      }
    };
    let secondSelectionCollection = {
      1: {
        id: 1,
        value: 10
      },
      41: {
        id: 41,
        value: 31
      },
      2: {
        id: 2,
        value: 'test1'
      }
    };
    let thirdSelectionCollection = {
      2: {
        id: 2,
        value: 'test'
      },
      41: {
        id: 41,
        value: 2
      }
    };
    let expectedResult =
    [
      {
        bindKey: "312_1_10",
        isMultipartOption: false,
        key: "312_1",
        name: 'Test Option 1',
        origOption: {
          id: 1,
          value: 123
        },
        placeholder: 123,
        value: 10,
      },
      {
        bindKey: "312_2_test2",
        isMultipartOption: true,
        key: "312_2",
        name: 'Test Option 2 (test2)',
        origOption: {
          id: 2,
          value: 'test|test1|test2'
        },
        parts: [
          {
            isActive: false,
            key: "312_2_0"
          },
          {
            isActive: false,
            key: "312_2_1"
          },
          {
            isActive: true,
            key: "312_2_2"
          },
        ],
        value: 'test2'
      },
      {
        bindKey: "312_41_2",
        isMultipartOption: false,
        key: "312_41",
        name: 'Test Option 41',
        origOption: {
          id: 41,
          value: 125
        },
        placeholder: 125,
        value: 2,
      },
    ];
    let result = OptionFunctions.generateCurrentOptions(optionId, true, possibleOptions, firstSelectionCollection, secondSelectionCollection, thirdSelectionCollection);

    expect(result).toMatchObject(expectedResult);
  });
});
