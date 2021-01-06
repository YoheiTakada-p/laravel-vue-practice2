<template>
  <div class="photo">
    <figure class="photo__wrappwe">
      <img src="" alt="" class="photo__image" />
    </figure>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
};
</script>
