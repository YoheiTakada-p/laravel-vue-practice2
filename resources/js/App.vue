<template>
  <div>
    <header>
      <Navbar />
    </header>
    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";

export default {
  components: {
    Navbar,
    Footer,
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === 500) {
          this.$router.push("/500");
        }
      },
      immediate: true,
    },
    route: function () {
      this.$store.commit("error/setCode", null);
    },
  },
};
</script>
