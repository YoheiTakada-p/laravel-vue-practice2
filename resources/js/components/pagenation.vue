<template>
  <div class="pagination">
    <RouterLink
      v-if="!isFirstPage"
      v-bind:to="`/?page=${currentPage - 1}`"
      class="button"
      >&laquo; prev</RouterLink
    >
    <RouterLink
      v-if="!isLastPage"
      v-bind:to="`/?page=${currentPage + 1}`"
      class="button"
      >next &raquo;</RouterLink
    >
  </div>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
  },
  computed: {
    isFirstPage: function () {
      return this.currentPage === 1;
    },
    isLastPage: function () {
      return this.currentPage === this.lastPage;
    },
  },
};
</script>
