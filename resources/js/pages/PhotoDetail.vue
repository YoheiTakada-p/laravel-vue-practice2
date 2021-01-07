<template>
  <div
    v-if="photo"
    class="photo-detail"
    :class="{ 'photo-detail--column': fullWidth }"
  >
    <figure
      class="photo-detail__pane photo-detail__image"
      v-on:click="fullWidth = !fullWidth"
    >
      <img v-bind:src="photo.url" alt="" />
      <figcaption>Posted by {{ photo.owner.name }}</figcaption>
    </figure>
    <div class="photo-detail__pane">
      <button class="button button--like" title="Like photo">
        <i class="icon ion-md-heart"></i>12
      </button>
      <a
        v-bind:href="'/photos/' + photo.id + '/download'"
        class="button"
        title="Download photo"
      >
        <i class="icon ion-md-arrow-round-down"></i>Download
      </a>
      <h2 class="photo-detail__title">
        <i class="icon ion-md-chatboxes"></i>Comments
      </h2>
      <ul class="photo-detail__comments" v-if="photo.comments.length > 0">
        <li
          class="photo-detail__commentItem"
          v-for="comment in photo.comments"
          :key="comment.content"
        >
          <p class="photo-detail__commentBody">
            {{ comment.content }}
          </p>
          <p class="photo-detail__commentInfo">
            {{ comment.author.name }}
          </p>
        </li>
      </ul>
      <p v-else>No comments yet.</p>
      <form class="form" v-on:submit.prevent="addComment" v-if="isLogin">
        <div class="error" v-if="commentErrors">
          <ul v-if="commentErrors.content">
            <li v-for="msg in commentErrors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <textarea
          class="form__item"
          v-bind:value="commentContent"
          v-on:input="commentContent = $event.target.value"
        ></textarea>
        <div class="form__button">
          <button type="submit" class="button button--inverse">
            submit comment
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data: function () {
    return {
      photo: null,
      fullWidth: false,
      commentContent: "",
      commentErrors: null,
    };
  },
  methods: {
    fetchPhoto: async function () {
      const response = await axios.get("/api/photos/" + this.id);

      this.photo = response.data;
    },
    addComment: async function () {
      const response = await axios
        .post("/api/photos/" + this.id + "/comments", {
          content: this.commentContent,
        })
        .catch((error) => error.response);

      if (response.status === 422) {
        this.commentErrors = response.data.errors;
        return false;
      }

      this.commentContent = "";
      this.commentErrors = null;

      if (response.status !== 201) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photo.comments = [response.data, ...this.photo.comments];
    },
  },
  computed: {
    isLogin: function () {
      return this.$store.getters["auth/check"];
    },
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchPhoto();
      },
      immediate: true,
    },
  },
};
</script>
