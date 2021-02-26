<template>
  <div class="card m-3">
    <div class="card-body">
      <div class="row justify-content-center align-items-center">
        <div :class="'col-4 ' + orderDivImage(idx)">
          <img
            :src="storageURL + post.image"
            :alt="post.title"
            class="img-fluid img-thumbnail"
          />
        </div>
        <div :class="'col-8 ' + orderDivText(idx)">
          <!-- <router-link :to="{ name: 'post.show', params: { post: post.id } }"> -->
            <h5 class="text-uppercase font-weight-bold text">
              {{ post.title }}
            </h5>
          <!-- </router-link> -->
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
      imageOrder: "",
      textOrder: "",
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