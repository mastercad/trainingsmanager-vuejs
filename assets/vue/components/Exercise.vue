<template>
  <div class="card w-100 mt-2">
    <div class="row">
      <div class="col-md-8">
        {{ origName }}
      </div>
      <div class="col-md-8">
        {{ origSeoLink }}
      </div>
      <div class="col-md-8">
        {{ origDescription }}
      </div>
      <div class="col-md-8">
        {{ origSpecialFeatures }}
      </div>
      <div class="col-md-8">
        {{ origPreviewPicturePath }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      default: -99999
    },
    name: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    },
    seoLink: {
      type: String,
      required: true
    },
    specialFeatures: {
      type: String,
      required: true
    },
    previewPicturePath: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      origId: this.id,
      origName: this.name,
      origDescription: this.description,
      origSeoLink: this.seoLink,
      origSpecialFeatures: this.specialFeatures,
      origPreviewPicturePath: this.previewPicturePath
    }
  },
  methods: {
    async updateExercise() {
      const result = await this.$store.dispatch(
        "exercises/update",
        {
          id: this.id,
          name: this.origName,
          description: this.origDescription,
          seoLink: this.origSeoLink,
          specialFeatures: this.origSpecialFeatures,
          previewPicturePath: this.origPreviewPicturePath
        }
      );
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.description = "";
        this.$data.seoLink = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
      }
    },
    async deleteExercise() {
      const result = await this.$store.dispatch("exercises/delete", this.id);
      if (result !== null) {
        this.$data.id = -9999;
        this.$data.name = "";
        this.$data.description = "";
        this.$data.seoLink = "";
        this.$data.specialFeatures = "";
        this.$data.previewPicturePath = "";
      }
    }
  }
};
</script>
