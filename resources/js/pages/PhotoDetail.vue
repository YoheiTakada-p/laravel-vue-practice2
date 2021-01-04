<template>
  <div>
    <h1>Photo Detail</h1>
    <h2>{{ id }}</h2>
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
};
</script>
