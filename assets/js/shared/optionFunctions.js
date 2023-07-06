const OptionFunctions = {
  resultOptions: [],
  possibleOptions: [],
  currentSelectedOptions: [],
  additionalOptions: [],
  identifier: '',
  showLabelAndValue: false,
  // übergabe ist:
  // identifier: identifier der aktuellen option
  // possibleOptions: mögliche optionen (z.b. deviceOptions)
  // currentSelectedOptions: aktuell gewählte optionen (z.b. deviceXDeviceOptions)
  // additionalOptions: weitere mögliche collections in denen sich optionen befinden können (z.b. in den Trainingsplänen, diese werden aber nur als platzhalter angezeigt)
  generateCurrentOptions: function(identifier, showLabelAndValue, possibleOptions, currentSelectedOptions, ...additionalOptions) {
    this.identifier = identifier;
    this.showLabelAndValue = showLabelAndValue;
    this.possibleOptions = possibleOptions;
    this.currentSelectedOptions = currentSelectedOptions;
    this.additionalOptions = additionalOptions;
    this.resultOptions = [];

    if (!OptionFunctions.isIterable(this.possibleOptions)) {
      return this.resultOptions;
    }

    this.considerPossibleOptions();

    return this.resultOptions;
  },
  considerPossibleOptions: function() {
    for (const key in this.possibleOptions) {
      let possibleOption = this.possibleOptions[key];
      let value = this.retrieveCurrentValue(possibleOption.id);
      let name = this.showLabelAndValue ? possibleOption.name : 'None';
      let isMultipartOption = OptionFunctions.isMultipartOption(possibleOption.value);
      let outerKey = this.identifier+'_'+possibleOption.id;
      let optionInformation = {
        key: outerKey,
        name: name,
        isMultipartOption: isMultipartOption,
        origOption: possibleOption,
        bindKey: outerKey+'_'+value,
        value: value,
      };

      if (isMultipartOption) {
        optionInformation = this.handleMultiPartOptions(optionInformation, possibleOption, value);
      } else {
        optionInformation.placeholder = possibleOption.value;
      }

      this.resultOptions.push(optionInformation);
    }

    return this;
  },
  retrieveCurrentValue: function(possibleOptionId) {
    if (undefined !== this.currentSelectedOptions && undefined !== this.currentSelectedOptions[possibleOptionId]) {
      return this.currentSelectedOptions[possibleOptionId].value;
    }

    if (undefined !== this.additionalOptions) {
      return OptionFunctions.investigateCurrentOptionValue(possibleOptionId, false, ...this.additionalOptions);
    }

    return null;
  },
  handleMultiPartOptions: function(optionInformation, possibleOption, value) {
    optionInformation.parts = OptionFunctions.splitOption(possibleOption.value);
    optionInformation.parts.forEach((partValue, index) => {
      let key = OptionFunctions.generateOptionPartKey(possibleOption.id, index);
      let isActive = partValue == value;

      if (isActive
        && this.showLabelAndValue
      ) {
        optionInformation.name = optionInformation.name + " ("+partValue+")";
      } else if (isActive) {
        optionInformation.name = partValue;
      }

      optionInformation.parts[index] = {
        isActive: isActive,
        key: key,
        value: partValue
      };
    });

    return optionInformation;
  },
  splitOption: function(value) {
    return value.split('|');
  },
  isMultipartOption: function(value) {
    const regex = /\|/;
    let regexResult = regex.exec(value);
    let result = regexResult && 0 < regexResult.length;

    return result ? true : false;
  },
  generateOptionPartKey: function(deviceOptionId, index) {
    return this.identifier+'_'+deviceOptionId+'_'+index;
  },
  investigateCurrentOptionValue: function(optionId, allowMultipartValue, ...possibleOptions) {
    let returnValue = null;
    possibleOptions.forEach((optionCollection) => {
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
  prepareOptionCollection: function(options, extractIdClosure, extractValueClosure)
  {
    let result = {};
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
  isIterable: function(input){
    return null !== input
      && (
        typeof input == Array
        || typeof input === 'object'
        || Symbol.iterator in Object(input)
      );
  }
};

module.exports = OptionFunctions;
