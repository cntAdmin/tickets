<template>
  <div class="mx-2 mx-xl-5 mb-4">
    <div v-for="(comment, idx) in comments" :key="idx">
      <ticket-comment
        :comment="comment"
        :key="comment.id"
        :isFaq="isFaq"
        @succeeded="succeeded"
      ></ticket-comment>
    </div>
  </div>
</template>

<script>
export default {
  props: ["comments", "isFaq"],
  mounted(){
    // console.log(this.comments)
  },
  methods: {
    succeeded(data) {
      // console.log('datacomments', data);
      this.$emit("succeeded", data);
    },
  },
};
</script>