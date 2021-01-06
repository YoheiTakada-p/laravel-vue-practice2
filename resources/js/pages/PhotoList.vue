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

      console.log(response.data.current_page);
      this.photos = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
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
