<template>
  <footer class="footer">
    <button class="button button--link" v-on:click="logout" v-if="isLogin">
      Logout
    </button>
    <RouterLink class="button button--link" to="/login" v-else>
      Login / Register
    </RouterLink>
  </footer>
</template>

<script>
export default {
  methods: {
    logout: function () {
      this.$store
        .dispatch("auth/logout")
        .then(this.$router.push("/").catch((err) => {}));
    },
  },
  computed: {
    isLogin: function () {
      return this.$store.getters["auth/check"];
    },
  },
};
</script>
