<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/"> Vuesplash </RouterLink>
    <div class="navbar__menu">
      <div class="navbar__item" v-if="isLogin">
        <button class="button" v-on:click="showForm = !showForm">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>
      <span class="navbar__item" v-if="isLogin">{{ username }}</span>
      <div class="navbar__item" v-else>
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </div>
    <PhotoForm v-bind:value="showForm" v-on:input="showForm = $event" />
  </nav>
</template>

<script>
import PhotoForm from "./PhotoForm.vue";

export default {
  components: {
    PhotoForm,
  },
  data: function () {
    return {
      showForm: false,
    };
  },
  computed: {
    isLogin: function () {
      return this.$store.getters["auth/check"];
    },
    username: function () {
      return this.$store.getters["auth/username"];
    },
  },
};
</script>
