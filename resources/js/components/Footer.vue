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
    logout: async function () {
      await this.$store.dispatch("auth/logout");

      if (this.apiStatus) {
        this.$router.push("/").catch((err) => {});
      }
    },
  },
  computed: {
    isLogin: function () {
      return this.$store.getters["auth/check"];
    },
    apiStatus: function () {
      return this.$store.state.auth.apiStatus;
    },
  },
};
</script>
