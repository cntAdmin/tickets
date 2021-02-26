<template>
  <div class="row justify-content-start mt-3">
    <div class="col-12 col-md-6" v-for="(post, idx) in posts" :key="post.id">
      <thumbnail-post
        :post="post"
        :idx="idx"
      ></thumbnail-post>
    </div>
  </div>
</template>

<script>
export default {
  props: ["posts"],
};
</script>

<style>
</style>