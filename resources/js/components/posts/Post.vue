<template>
  <div class="row justify-content-between mx-3 my-3">
    <div class="col-10 order-sm-1 order-md-0">
      <div class="card shadow" style="width: 35rem;" v-if="post.attachments[0]">
        <div v-if="post.attachments[0].path.includes('.mp4','.bin', '.bin', '.mkv', '.avi')">
          <div class="embed-responsive embed-responsive-16by9">
            <video controls class="embed-responsive-item">
              <source :src="'/storage/media/'+post.attachments[0].path" type="video/mp4">
            </video>
          </div>
        </div>
        <div v-else>
          <img  
            class="card-img-top"
            :src="'/storage/media/'+post.attachments[0].path"
            :alt="post.title"
          />
        </div>
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
                  {{ post.created_by.name }} /
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

    <div class="col-2 order-sm-1 order-md-0">
      <a href="/blog" class="btn btn-lg btn-orange text-white">Volver</a>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      post: {
        created_by: {},
      },
    };
  },
  beforeMount() {
    this.getPost();
  },
  methods: {
    getPost() {
      axios
        .get(`/api/post/${this.$route.params.post}`)
        .then((res) => {
          if (res.data.success) {
            this.post = res.data.post;
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>

<style>
</style>