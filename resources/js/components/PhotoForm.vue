<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <form v-on:submit.prevent="submit" class="form">
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
      photo: null,
    };
  },
  methods: {
    onFileChange: function (event) {
      if (event.target.files.length === 0) {
        this.reset();
        return false;
      }

      if (!event.target.files[0].type.match("image.*")) {
        this.reset();
        return false;
      }

      const reader = new FileReader();

      reader.onload = (e) => {
        this.preview = e.target.result;
      };

      reader.readAsDataURL(event.target.files[0]);
      this.photo = event.target.files[0];
    },
    reset: function () {
      this.preview = null;
      this.$el.querySelector("input[type='file']").value = null;
      this.photo = null;
    },
    submit: async function () {
      const formData = new FormData();

      formData.append("photo", this.photo);

      const response = await axios.post("/api/photos", formData);

      this.reset();
      this.$emit("input", false);
    },
  },
};
</script>
