<template>
  <div
    class="col-12"
    v-if="featuredPosts && Object.keys(featuredPosts).length > 0"
  >
    <thumbnail-post
      v-for="(post, idx) in featuredPosts"
      :key="post.id"
      :post="post"
      :idx="idx"
    ></thumbnail-post>
  </div>
</template>

<script>
export default {
  props: [""],
  data() {
    return {
      featuredPosts: [],
    };
  },
  activated() {},
  mounted() {
    this.getFeaturedPosts();
  },
  methods: {
    getFeaturedPosts() {
      axios
        .get("/api/featured_post", {
          params: {
            type: 'featured',
            limit: 3,
          },
        })
        .then((res) => {
          console.log(res.data);
          if(res.data.success) {
            this.featuredPosts = res.data.posts;
          }
        });
    },
  },
};
</script>