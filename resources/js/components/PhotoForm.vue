<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <form action="" class="form">
      <input type="file" class="form__item" v-on:change="onFileChange" />
      <output class="form__output" v-if="preview">
        <img v-bind:src="preview" alt="" class="form__output" />
      </output>
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean,
      required: true,
    },
  },
  data: function () {
    return {
      preview: null,
    };
  },
  methods: {
    onFileChange: function (event) {
      if (event.target.files.length === 0) {
        return false;
      }

      if (!event.target.files[0].type.match("image.*")) {
        return false;
      }

      const reader = new FileReader();

      reader.onload = (e) => {
        this.preview = e.target.result;
      };

      reader.readAsDataURL(event.target.files[0]);
    },
  },
};
</script>
