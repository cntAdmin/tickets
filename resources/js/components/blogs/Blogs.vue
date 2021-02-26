<template>
  <div class="row justify-content-center mx-3">
    <blog-header title="AAP-TC INFORMACIÓN"></blog-header>
    
    <thumbnail-posts :posts="featuredPosts.data"></thumbnail-posts>
    
    <div class="row justify-content-center mt-5">
      <pagination
        :data="featuredPosts"
        @pagination-change-page="getFeaturedPosts"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      featuredPosts: {},
    };
  },
  activated() {
    this.getFeaturedPosts();
  },
  methods: {
    getFeaturedPosts(page) {
      axios
        .get("/api/featured_post", {
          params: {
            page: page ? page : 1,
          },
        })
        .then((res) => {
          this.featuredPosts = res.data.posts;
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>