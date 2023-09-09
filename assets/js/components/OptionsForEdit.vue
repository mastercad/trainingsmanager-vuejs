<template>
  <b-card
    bg-variant="light"
    class="mt-2 shadow p-2 mb-3 bg-white rounded"
  >
    <b-form-group
      :id="type+'_options'"
      label-cols-lg="3"
      :label="capitalizeFirstLetter(type)+' Options'"
      label-size="lg"
      label-class="font-weight-bold pt-0"
      class="mb-0"
      :description="description"
    >
      <b-row
        v-for="option in prepareOptions"
        :id="type+'_option_container_'+option.key"
        :key="option.key"
        :class="type+'_option'"
      >
        <b-col
          sm="5"
          style="display: flex; align-items: center;"
        >
          <label
            :for="type+'_option_'+option.key"
          >
            <span style="vertical-align: middle;">
              {{ option.origOption.origEntry.name }}:
            </span>
          </label>
        </b-col>

        <b-col sm="7">
          <b-dropdown
            v-if="option.isMultipartOption"
            :id="type+'_option_'+option.key"
            split-variant="outline-primary"
            variant="primary"
            class="m-md-2"
            :text="option.name"
          >
            <b-dropdown-item
              :id="type+'_option_null'"
              :key="type+'_option_null'"
              :active="null === option.value"
              @click="saveOptionSelection({value: null}, option)"
            >
              None
            </b-dropdown-item>

            <b-dropdown-item
              v-for="optionPart in option.parts"
              :id="type+'_option_'+optionPart.key"
              :key="currentSelectedOption+'_option_'+optionPart.key"
              :active="optionPart.isActive"
              @click="saveOptionSelection(optionPart, option)"
            >
              {{ optionPart.value }}
            </b-dropdown-item>
          </b-dropdown>
          <div v-else>
            <input
              :id="type+'_option_'+option.key"
              class="form-control"
              :placeholder="option.placeholder"
              type="text"
              :value="option.value"
              @input="saveOptionSelection({value: $event.target.value}, option)"
            >
          </div>
        </b-col>
      </b-row>
    </b-form-group>
  </b-card>
</template>

<script>

import OptionFunctions from "../shared/optionFunctions.js";

export default {
  name: "OptionsForEdit",
  props: {
    // exercise or device, is used to get correct source for options from collection
    type: {
      type: String,
      required: true
    },
    // value of id for device or exercise
    identifier: {
      type: [Number, String],
      required: true
    },
    possibleOptions: {
      type: Array,
      required: true
    },
    currentOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    additionalOptions: {
      type: Array,
      default: () => { return new Array(); }
    },
    description: {
      type: String,
      default: ''
    },
    showLabelAndValue: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      passedPossibleOptions: this.possibleOptions,
      passedCurrentOptions: this.currentOptions,
      currentSelectedOptions: [],
      currentSelectedOption: ''
    }
  },
  computed: {
    prepareOptions () {
      let preparedAdditionalOptions = [];
      if (this.additionalOptions) {
        preparedAdditionalOptions = OptionFunctions.prepareOptionCollection(this.additionalOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;})
      }

      let generatedOptions = OptionFunctions.generateCurrentOptions(
        this.identifier,
        this.showLabelAndValue,
        OptionFunctions.prepareOptionCollection(this.possibleOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;}),
        // OptionFunctions.prepareOptionCollection(this.currentOptions, function(option) {return option.id;}, function(option) {return option.defaultValue;}),
        this.currentSelectedOptions,
        preparedAdditionalOptions
      );

      console.log(generatedOptions);

      return generatedOptions;
    }
  },
  created() {
    this.currentSelectedOptions = {};
    for (let currentOptionPosition in this.currentOptions) {
      let currentOption = this.currentOptions[currentOptionPosition][this.type+"Option"];
      let id = currentOption.id;
//      this.currentSelectedOptions[this.currentOptions[currentOptionPosition][this.type+"Option"].id] = this.currentOptions[currentOptionPosition][this.type+"OptionValue"];
      this.currentSelectedOptions[id] = {
        id: id,
        value: this.currentOptions[currentOptionPosition][this.type+"OptionValue"],
        origEntry: currentOption
      };
    }
  },
  methods: {
    saveOptionSelection(option, preparedOption) {
      let currentExistingOption = this.retrieveExistingOption(option, preparedOption);
      if (null === option.value
          || 0 === option.value.length
      ) {
        this.refreshInputs(option, preparedOption);

        return;
      }

      if (currentExistingOption) {
        currentExistingOption[this.type+"OptionValue"] = option.value;
        this.refreshInputs(option, preparedOption);

        return;
      }

      this.passedCurrentOptions.push(this.generateNewOption(option.value, preparedOption.origOption.id));
      this.refreshInputs(option, preparedOption);
    },
    retrieveExistingOption(option, preparedOption) {
      let currentExistingOption = null;
      for (let passedCurrentOptionPosition in this.passedCurrentOptions) {
        // if id is undefined, is expected iri is set
        let currentId = this.extractId(
          this.passedCurrentOptions[passedCurrentOptionPosition][this.type+"Option"].id ? this.passedCurrentOptions[passedCurrentOptionPosition][this.type+"Option"].id : this.passedCurrentOptions[passedCurrentOptionPosition][this.type+"Option"],
          preparedOption.origOption.id
        );
        if (currentId === preparedOption.origOption.id) {
          currentExistingOption = this.passedCurrentOptions[passedCurrentOptionPosition];
          if (null === option.value
            || 0 === option.value.length
          ) {
            this.passedCurrentOptions.splice(passedCurrentOptionPosition, 1);
          }
          break;
        }
      }

      return currentExistingOption;
    },
    generateNewOption(value, mainOptionId) {
      let newOption = {};
      newOption["id"] = null;
      newOption[this.type+"OptionValue"] = value;
      newOption[this.type+"Option"] = '/api/'+this.type+'_options/'+mainOptionId;

      if (this.isValidId(this.identifier)) {
        newOption[this.type] = '/api/'+this.type+'s/'+this.identifier;
      }

      return newOption;
    },
    refreshInputs(option, preparedOption) {
      preparedOption.value = option.value;

      if (null === option.value
        || 0 === option.value.length
      ) {
        if (this.showLabelAndValue) {
          preparedOption.name = preparedOption.origOption.origEntry.name;
        } else {
          preparedOption.name = 'None';
        }
        this.currentSelectedOption = preparedOption.key+'_'+option.value;
      } else if (false === preparedOption.isMultipartOption) {
        this.currentSelectedOption = preparedOption.key+'_'+option.value;

        return;
      } else if (this.showLabelAndValue) {
        preparedOption.name = preparedOption.origOption.origEntry.name+" ("+option.value+")";
        this.currentSelectedOption = option.key+'_'+option.value;
      } else {
        preparedOption.name = option.value;
        this.currentSelectedOption = option.key+'_'+option.value;
      }

      console.log("PREPARED OPTION NAME: "+preparedOption.name);

      preparedOption.parts.forEach( part => {
        part.isActive = false;
      });

      option.isActive = true;
    },
    extractId(id, possibleExpectedId) {
      if (typeof id === 'number') {
        return id;
      }

      let regEx = new RegExp('/('+possibleExpectedId+')$', 'g');
      if ((typeof id === 'string' || id instanceof String)) {
        let match = (id.match(regEx) || []).map(e => e.replace(regEx, '$1'));
        console.log(match[0]);

        return parseInt(match[0]);
      }
    },
    isValidId(id) {
      return id && (typeof id === 'number' || !isNaN(id));
    },
    isGenericId(id) {
      return id && ((typeof id === 'string' || id instanceof String) && id.startsWith(this.type+'_'));
    },
    capitalizeFirstLetter(word) {
      return word.charAt(0).toUpperCase() + word.slice(1);
    }
  }
};
</script>