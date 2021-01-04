<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <div v-show="loading" class="panel">
      <Loader>Seding your photo...</Loader>
    </div>
    <form v-on:submit.prevent="submit" class="form" v-show="!loading">
      <div class="errors" v-if="errors">
        <ul v-if="errors.photo">
          <li v-for="error in errors.photo" :key="error">{{ error }}</li>
        </ul>
      </div>
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
import Loader from "./Loader.vue";

export default {
  components: {
    Loader,
  },
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
      errors: null,
      loading: false,
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
      this.loading = true;

      const formData = new FormData();
      formData.append("photo", this.photo);
      const response = await axios
        .post("/api/photos", formData)
        .catch((error) => error.response);

      this.loading = false;

      //422エラーならエラーメッセージを返す
      if (response.status === 422) {
        this.errors = response.data.errors;
        return false;
      }

      this.reset();
      this.$emit("input", false);

      //201以外ならエラー画面を返す
      if (response.status === 201) {
        this.$store.commit("message/setContent", {
          content: "You have successfully posted a photo!",
          timeout: 6000,
        });
        this.$router.push("/photos/" + response.data.id);
      } else {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
    },
  },
};
</script>
