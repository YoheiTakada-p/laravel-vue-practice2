<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
        class="grid__item"
        v-for="photo in photos"
        :key="photo.id"
        :item="photo"
      />
    </div>
  </div>
</template>

<script>
import Photo from "../components/Photo.vue";

export default {
  components: {
    Photo,
  },
  data: function () {
    return {
      photos: [],
    };
  },
  methods: {
    fetchPhotos: async function () {
      const response = await axios.get("/api/photos");

      if (response.status !== 200) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = response.data.data;
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
