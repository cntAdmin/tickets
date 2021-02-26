<template>
  <div class="row justify-content-between mx-3 mh">
    <div class="col-10 order-sm-1 order-md-0">
      <post-header :title="post.title" :image="post.image"></post-header>
      <div class="col-12 row justify-content-">
        <div class="mr-auto">
          {{ post.created_by.name }} - {{ post.created_at | moment('DD-MM-YYYY HH:mm:ss') }}
        </div>
        <div class="ml-auto">
          <button class="btn btn-sm btn-light"><span class="font-weight-bold">{{ post.likes }}</span><i class="far fa-thumbs-up fa-2x ml-2"></i></button>
          <button class="btn btn-sm btn-light"><span class="font-weight-bold">{{ post.dislikes }}</span><i class="far fa-thumbs-down fa-2x ml-2"></i></button>
        </div>
        <div class="dropdown-divider"></div>
        <div v-html="post.description"></div>

      </div>

    </div>
    <div class="col-2 order-sm-0 order-md-1">
      <featured-posts></featured-posts>
    </div>
    <div class="col-12 order-sm-2 order-md-2">
      <div class="dropdown-divider"></div>
      <other-posts :postID="post.id"></other-posts>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      post: {
        created_by:{}
      }
    }
  },
  beforeMount() {
    this.getPost();
  },
  methods: {
    getPost() {
      axios.get(`/api/post/${this.$route.params.post}`)
      .then( res => {
        if(res.data.success) {
          this.post = res.data.post;
        }
      }).catch(err => console.log(err));
    }
  }
}
</script>

<style>

</style>