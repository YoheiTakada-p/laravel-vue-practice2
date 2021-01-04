<template>
  <div>
    <header>
      <Navbar />
    </header>
    <main>
      <div class="container">
        <Message />
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Footer from "./components/Footer.vue";
import Message from "./components/Message.vue";

export default {
  components: {
    Navbar,
    Footer,
    Message,
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
