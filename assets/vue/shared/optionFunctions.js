const OptionFunctions = {
  // @TODO hier exercise und device übergaben entfernen und an stelle dessen weiter mögliche options kommagetrennt übergeben
  // current possible options sind alle möglichkeiten die in der db stehen
  // current selected options sind die optionen die bereits gewählt sind (hier für trainingplan und z.b. exercise)
  // alle weiteren übergaben sind, absteigend in priorität, die möglichen optionen als default (exercise_x_device_option, device_option)
  // übergabe ist:
  // identifier: name des identifiers der aktuellen option
  // possibileOptions: mögliche optionen (z.b. deviceOptions)
  // currentSelectedOptions: aktuell gewählte optionen (z.b. deviceXDeviceOptions)
  // additionalOptions: weitere mögliche collections in denen sich optionen befinden können (z.b. in den Trainingsplänen, diese werden aber nur als platzhalter angezeigt)
  generateCurrentOptions: (identifier, possibleOptions, currentSelectedOptions, ...additionalOptions) => {
    let resultOptions = [];

    if (!OptionFunctions.isIterable(possibleOptions)) {
      return resultOptions;
    }

    if (undefined == currentSelectedOptions) {
      return resultOptions;
    }

    for (const key in possibleOptions) {
      let possibleOption = possibleOptions[key];
      let value = (undefined !== currentSelectedOptions[possibleOption.id])
        ? currentSelectedOptions[possibleOption.id]
        : OptionFunctions.investigateCurrentOptionValue(possibleOption.id, false, ...additionalOptions);

      let name = possibleOption.origEntry.name;
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
        optionInformation.parts.forEach((partValue, index) => {
          let key = OptionFunctions.generateOptionPartKey(identifier, possibleOption.id, index);
          let isActive = partValue == value;

          if (isActive) {
            optionInformation.name = optionInformation.name + " ("+partValue+")";
          }

          optionInformation.parts[index] = {
            isActive: isActive,
            key: key,
            value: partValue
          };
        });
      } else {
        optionInformation.placeholder = possibleOption.value;
      }

      resultOptions.push(optionInformation);
    }

    return resultOptions;
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
