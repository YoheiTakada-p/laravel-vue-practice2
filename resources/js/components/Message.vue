<template>
  <div class="message" v-show="message">{{ message }}</div>
</template>

<script>
import { mapState } from "vuex";

export default {
  computed: {
    message() {
      return this.$store.state.message.content;
    },
    // ...mapState({
    //   message: (state) => state.message.content,
    // }),
  },
};
</script>
