<template>
  <div class="row justify-content-between mx-3 my-3">
    <div class="col-10 order-sm-1 order-md-0">
      <div class="card shadow" style="width: 35rem;" v-if="post.attachments[0]">
        <img
            class="card-img-top" 
            :src="'/storage/media/'+post.attachments[0].path"
            :alt="post.title" 
        />
        <div class="card-header">
          <div class="row justify-content-center align-items-center">
            <h2 class="col-12 text-center text-uppercase font-weight-bold">
              {{ post.title }}
            </h2>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div class="row justify-content-between align-items-center">
              <div class="mr-auto">
                <span class="font-weight-bold">
                  {{ post.created_by.name }} -
                  {{ post.created_at | moment("DD-MM-YYYY HH:mm:ss") }}
                </span>
              </div>
              <div class="ml-auto">
                <button class="btn btn-sm btn-light">
                  <span class="font-weight-bold">{{ post.likes }}</span
                  ><i class="far fa-thumbs-up fa-2x ml-2"></i>
                </button>
                <button class="btn btn-sm btn-light">
                  <span class="font-weight-bold">{{ post.dislikes }}</span
                  ><i class="far fa-thumbs-down fa-2x ml-2"></i>
                </button>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="row mt-3">
              <div v-html="post.description"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow" style="width: 35rem;" v-else>
        <div class="card-header">
          <div class="row justify-content-center align-items-center">
            <h2 class="col-12 text-center text-uppercase font-weight-bold">
              {{ post.title }}
            </h2>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div class="row justify-content-between align-items-center">
              <div class="mr-auto">
                <span class="font-weight-bold">
                  {{ post.created_by.name }} -
                  {{ post.created_at | moment("DD-MM-YYYY HH:mm:ss") }}
                </span>
              </div>
              <div class="ml-auto">
                <button class="btn btn-sm btn-light">
                  <span class="font-weight-bold">{{ post.likes }}</span
                  ><i class="far fa-thumbs-up fa-2x ml-2"></i>
                </button>
                <button class="btn btn-sm btn-light">
                  <span class="font-weight-bold">{{ post.dislikes }}</span
                  ><i class="far fa-thumbs-down fa-2x ml-2"></i>
                </button>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="row mt-3">
              <div v-html="post.description"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="col-2 order-sm-0 order-md-1">
      <div class="card shadow">
        <div class="card-header d-flex justify-content-center align-items-center">
          <h4 class="my-auto font-weight-bold">DESTACADOS</h4>
        </div>
        <div class="card-body p-0 pb-3 bg-transparent">
          <featured-posts></featured-posts>
        </div>
      </div>
    </div> -->

    <div class="col-2 order-sm-1 order-md-0">
      <a href="/blog" class="btn btn-lg btn-info text-white">Volver</a>
    </div>
                
    <!-- <div class="dropdown-divider"></div>
    
    <div class="col-sm-12 col-md-4 order-sm-2 order-md-2">
      <thumbnail-post
        v-for="(post, idx) in otherPosts"
        :key="post.id"
        :post="post"
        :idx="idx"
      ></thumbnail-post>
    </div> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      otherPosts: [],
      post: {
        created_by: {},
      },
    };
  },
  beforeMount() {
    this.getPost();
  },
  methods: {
    getOtherPosts() {
      axios
        .get(`/api/get_other_posts/${this.post.id}`)
        .then((res) => {
          if (res.data.success) {
            this.otherPosts = res.data.posts;
          }
        })
        .catch((err) => console.log(err));
    },
    getPost() {
      axios
        .get(`/api/post/${this.$route.params.post}`)
        .then((res) => {
          if (res.data.success) {
            this.post = res.data.post;
            this.getOtherPosts();
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>

<style>
</style>