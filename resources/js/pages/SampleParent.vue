<template>
  <div>
    <SampleChild v-bind:value="parentMsg" v-on:input="parentMsg = $event" />
    <div>入力内容: {{ parentMsg }}</div>
    <button v-on:click="onClickRandomButton">ランダム</button>
  </div>
</template>

<script>
import SampleChild from "./SampleChild.vue";

export default {
  name: "ParentSample",
  components: {
    SampleChild,
  },
  data: function () {
    return {
      parentMsg: "",
      fruits: [
        "オレンジ",
        "バナナ",
        "グレープフルーツ",
        "クランベリー",
        "レモン",
        "マンゴー",
        "キウイフルーツ",
      ],
    };
  },
  methods: {
    onClickRandomButton: function () {
      const randomIndex = Math.floor(Math.random() * this.fruits.length);
      this.parentMsg = this.fruits[randomIndex];
    },
  },
};
</script>
