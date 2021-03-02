<template>
  <div class="col-12 col-lg-4 col-xl-3 mt-3">
    <div class="row justify-content-center">
      <div class="card shadow" style="width: 18rem">
        <img
          class="card-img-top"
          :src="'/storage/' + post.image"
          :alt="post.title"
        />
        <div class="card-body">
          <h5 class="card-title font-weight-bold clamped">{{ post.title }}</h5>
        </div>
        <div class="card-footer">
          <router-link
            :to="{ name: 'post.show', params: { post: post.id } }"
            class="btn btn-primary btn-sm btn-block"
            >Saber más...
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post", "idx"],
  data() {
    return {
      storageURL: "/storage/",
    };
  },
  methods: {
    orderImage(idx) {
      if (idx % 2 === 0) {
        return "justify-content-md-end";
      }
      return "justify-content-md-start";
    },
    orderDivImage(idx) {
      if (idx % 2 === 0) {
        return "order-md-0";
      } else {
        return "order-md-1";
      }
    },
    orderDivText(idx) {
      if (idx % 2 === 0) {
        return "order-md-1";
      } else {
        return "order-md-0";
      }
    },
  },
};
</script>

<style>
</style>