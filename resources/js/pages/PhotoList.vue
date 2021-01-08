<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
        class="grid__item"
        v-for="photo in photos"
        :key="photo.id"
        :item="photo"
        v-on:like="onLikeClick"
      />
    </div>
    <Pagenation v-bind:current-page="currentPage" v-bind:last-page="lastPage" />
  </div>
</template>

<script>
import Photo from "../components/Photo.vue";
import Pagenation from "../components/pagenation.vue";

export default {
  components: {
    Photo,
    Pagenation,
  },
  props: {
    page: {
      type: Number,
      required: true,
      default: 1,
    },
  },
  data: function () {
    return {
      photos: [],
      currentPage: 0,
      lastPage: 0,
    };
  },
  methods: {
    fetchPhotos: async function () {
      const response = await axios.get(`/api/photos/?page=${this.page}`);
      if (response.status !== 200) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    },
    onLikeClick: function ({ id, liked }) {
      if (!this.$store.getters["auth/check"]) {
        alert("いいね機能を使うにはログインしてください。");
        return false;
      }

      if (liked) {
        this.unlike(id);
      } else {
        this.like(id);
      }
    },
    like: async function (id) {
      const response = await axios
        .put("/api/photos/" + id + "/like")
        .catch((error) => error.response);

      if (response.status !== 200) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = this.photos.map((photo) => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count += 1;
          photo.liked_by_user = true;
        }
        return photo;
      });
    },
    unlike: async function (id) {
      const response = await axios
        .delete("/api/photos/" + id + "/like")
        .catch((error) => error.response);

      if (response.status !== 200) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = this.photos.map((photo) => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count -= 1;
          photo.liked_by_user = false;
        }
        return photo;
      });
    },
  },
  //ページが切り替わった場合にキャッチしたいためwatchを使用
  watch: {
    $route: {
      async handler() {
        await this.fetchPhotos();
      },
      immediate: true,
    },
  },
};
</script>
