<template>
  <div class="mx-2 mx-xl-5 mb-4">
    <div v-for="(comment, idx) in comments" :key="idx">
      <ticket-comment
        :comment="comment"
        :key="comment.id"
        :user="user"
        :isFaq="isFaq"
        @succeeded="succeeded"
      ></ticket-comment>
    </div>
  </div>
</template>

<script>
export default {
  props: ["comments", "user", "isFaq"],
  methods: {
    succeeded(data) {
      // console.log('datacomments', data);
      this.$emit("succeeded", data);
    },
  },
};
</script>