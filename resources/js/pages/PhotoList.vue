<template>
  <div class="photo-list">
    <div class="grid">
      <Photo />
    </div>
  </div>
</template>

<script>
import Photo from "./Photo.vue";

export default {
  components: {
    Photo,
  },
};
</script>
