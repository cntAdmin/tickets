<template>
  <div class="row justify-content-center mx-3">
    <div
      class="bg-blue-gradient row justify-content-center align-items-center w-100 shadow"
      style="height: 100px"
    >
      <h2 class="font-weight-bolder text-white text-shadow-md">
        AAP-TC INFORMACIÓN
      </h2>
    </div>

    <div class="row justify-content-center mt-3 w-100">
      <thumbnail-post
        v-for="(post, idx) in featuredPosts.data"
        :key="post.id"
        :post="post"
        :idx="idx"
      ></thumbnail-post>
    </div>

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