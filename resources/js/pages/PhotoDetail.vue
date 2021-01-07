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
    };
  },
  methods: {
    fetchPhoto: async function () {
      const response = await axios.get("/api/photos/" + this.id);

      this.photo = response.data;
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
