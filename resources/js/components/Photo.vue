<template>
  <div class="photo">
    <figure class="photo__wrapper">
      <img
        class="photo__image"
        v-bind:src="item.url"
        v-bind:alt="'photo by ' + item.owner.name"
      />
      <RouterLink
        class="photo__overlay"
        v-bind:to="'/photos/' + item.id"
        v-bind:title="'View the photo by ' + item.owner.name"
      >
        <div class="photo__controls">
          <button
            class="photo__action photo__action--link"
            :class="{ 'photo__action--liked': item.liked_by_user }"
            title="Like photo"
            v-on:click="like"
          >
            <i class="icon ion-md-heart"></i>{{ item.likes_count }}
          </button>
          <a
            class="photo__action"
            title="Download photo"
            @click.stop
            :href="'/photos/' + item.id + '/download'"
          >
            <i class="icon ion-md-arrow-round-down"></i>
          </a>
        </div>
        <div class="photo__username">
          {{ item.owner.name }}
        </div>
      </RouterLink>
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
  methods: {
    like: function () {
      this.$emit("like", {
        id: this.item.id,
        liked: this.item.liked_by_user,
      });
    },
  },
};
</script>
