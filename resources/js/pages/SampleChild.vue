<template>
  <div>
    <label>
      果物：
      <input
        v-bind:value="msg"
        v-on:input="msg = $emit('input', $event.target.value)"
      />
    </label>
  </div>
</template>

<script>
export default {
  name: "ChildSample",
  props: {
    value: String,
  },
  computed: {
    msg: {
      get: function () {
        return this.value;
      },
      // set: function (newValue) {
      //   this.$emit("input", newValue);
      // },
    },
  },
};
</script>
