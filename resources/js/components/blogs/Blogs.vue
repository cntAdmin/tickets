<template>
  <div class="d-flex flex-column justify-content-center mx-lg-3">
    <div class="d-flex flex-row justify-content-center align-items-center bg-orange-gradient w-100 shadow" style="height: 100px">
      <h2 class="font-weight-bolder text-white text-shadow-md">
        CENTRO DE INFORMACIÓN ANDEL
      </h2>
    </div>
    <div class="card-columns mt-3"
      v-if="featuredPosts.data && Object.keys(featuredPosts.data).length > 0">
      <thumbnail-post
        v-for="(post, idx) in featuredPosts.data"
        :key="post.id"
        :post="post"
        :idx="idx"
      ></thumbnail-post>
    </div>
    <div v-else class="mt-3 mx-3 shadow">
      <div class="alert alert-warning text-center mb-0">¡Lo sentimos, todavía no hay nada en el Blog! Vuelva pronto para ver las novedades.</div>
    </div>
    <transition name="fade" v-if="searching" mode="out-in">
      <div class="d-flex justify-content-center my-5">
        <spinner></spinner>
      </div>
    </transition>

    <div class="row justify-content-center mt-5">
      <pagination
        v-if="
          featuredPosts.data !== undefined &&
          Object.keys(featuredPosts.data).length > 0
        "
        :data="featuredPosts"
        :limit="3"
        @pagination-change-page="getFeaturedPosts"
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
      featuredPosts: [],
      url: "/api/featured_post",
      searching: false,
      offset: 0,
      dontLoad: false,
    };
  },
  activated() {
    if (this.$screen.breakpoint === "xs") {
      this.url = "/api/featured_post_mobile";
      window.onscroll = () => {
        this.scroll();
      };
    }
    this.getFeaturedPosts();
  },
  methods: {
    scroll() {
      let bottomOfWindow =
        Number(
          (
            Math.max(
              window.pageYOffset,
              document.documentElement.scrollTop,
              document.body.scrollTop
            ) + window.innerHeight
          ).toFixed(0)
        ) === document.documentElement.offsetHeight;

      if (bottomOfWindow) {
        this.getFeaturedPosts(); // replace it with your code
      }
    },
    getFeaturedPosts(page) {
      if (!this.dontLoad && this.searching == false) {
        this.searching = true;

        axios
          .get(this.url, {
            params: {
              published: 1,
              page: page,
              offset: this.offset,
            },
          })
          .then((res) => {
            if (res.data.posts.length === 0) {
              this.searching = false;
              return (this.dontLoad = true);
            } else if (res.data.success) {
              if (this.$screen.breakpoint === "xs") {
                this.offset += 10;
                setTimeout(() => {
                  this.featuredPosts.data.push(...res.data.posts);
                  this.searching = false;
                }, 1000);
              } else {
                this.featuredPosts = res.data.posts;
                this.searching = false;
              }
            }
          })
          .catch((err) => console.log(err));
      }
    },
  },
};
</script>