const OptionFunctions = {
  // @TODO hier exercise und device übergaben entfernen und an stelle dessen weiter mögliche options kommagetrennt übergeben
  // current possible options sind alle möglichkeiten die in der db stehen
  // current selected options sind die optionen die bereits gewählt sind (hier für trainingplan und z.b. exercise)
  // alle weiteren übergaben sind, absteigend in priorität, die möglichen optionen als default (exercise_x_device_option, device_option)
  generateCurrentOptions: (identifier, possibleOptions, currentSelectedOptions, ...additionalOptions) => {
    let resultOptions = [];

    if (!OptionFunctions.isIterable(possibleOptions)) {
      return resultOptions;
    }

    for (const key in possibleOptions) {
      let possibleOption = possibleOptions[key];
      let value = undefined !== currentSelectedOptions && undefined !== currentSelectedOptions[possibleOption.id]
        ? currentSelectedOptions[possibleOption.id].value
        : OptionFunctions.investigateCurrentOptionValue(possibleOption.id, false, ...additionalOptions);

      let name = possibleOption.name;
      let isMultipartOption = OptionFunctions.isMultipartOption(possibleOption.value);
      let outerKey = identifier+'_'+possibleOption.id;
      let optionInformation = {
        key: outerKey,
        name: name,
        isMultipartOption: isMultipartOption,
        origOption: possibleOption,
        bindKey: outerKey+'_'+value,
        value: value,
      };

      if (isMultipartOption) {
        optionInformation.parts = OptionFunctions.splitOption(possibleOption.value);
        optionInformation.parts.forEach( (partValue, index) => {
          let key = OptionFunctions.generateOptionPartKey(identifier, possibleOption.id, index);
          let isActive = partValue == value;

          if (isActive) {
            optionInformation.name = optionInformation.name + " ("+value+")";
          }

          optionInformation.parts[index] = {
            isActive: isActive,
            key: key
          };
        });
      } else {
        optionInformation.placeholder = possibleOption.value;
      }

      resultOptions.push(optionInformation);
    }

    return resultOptions;
  },
  generateSelectedOptions(possibleOptions, currentSelectedOptions) {
    let selectedOptions = [];
    let preparedCurrentSelectedOptions = {};
    
    currentSelectedOptions.forEach(option => {
      preparedCurrentSelectedOptions[option.exerciseOption.id].value = option.optionValue;
    });

    for (const key in possibleOptions) {
      if (undefined !== preparedCurrentSelectedOptions[possibleOptions[key]]) {
        selectedOptions[possibleOptions[key].id] = preparedCurrentSelectedOptions[possibleOptions[key]].value;
      } else {
        selectedOptions[possibleOptions[key].id] = null;
      }
    }

    return selectedOptions;
  },
  splitOption(value) {
    return value.split('|');
  },
  isMultipartOption(value) {
    const regex = /\|/;
    let regexResult = regex.exec(value);
    let result = regexResult && 0 < regexResult.length;

    return result ? true : false;
  },
  generateOptionPartKey(identifier, deviceOptionId, index) {
    return identifier+'_'+deviceOptionId+'_'+index;
  },
  investigateCurrentOptionValue(optionId, allowMultipartValue, ...optionCollections) {
    var returnValue = null;
    optionCollections.forEach((optionCollection) => {
      if (undefined !== optionCollection[optionId]
        && optionCollection[optionId].value
        && (
          allowMultipartValue
          || !OptionFunctions.isMultipartOption(optionCollection[optionId].value)
        )
      ) {
        returnValue = optionCollection[optionId].value;
        return returnValue;
      }
    });
    return returnValue;
  },
  prepareOptionCollection(options, extractIdClosure, extractValueClosure)
  {
    var result = {};
    for(let option of options) {
      let id = extractIdClosure(option);
      let value = extractValueClosure(option);
      result[id] = {
        id: id,
        value: value,
        origEntry: option
      };
    }

    return result;
  },
  isIterable(input){
    return null !== input
      && (
        typeof input === Array
        || typeof input === 'object'
        || Symbol.iterator in Object(input)
      );
  }
};

module.exports = OptionFunctions;
