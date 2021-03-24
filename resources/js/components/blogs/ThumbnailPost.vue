<template>
  <div>
    <div class="card shadow mt-3">
      <img
        class="card-img-top"
        :src="'/storage/media/' + post.attachments > 0 ? post.attachments[0].path : ''"
        :alt="post.title"
      />
      <div class="card-body">
        <h5 class="card-title font-weight-bold clamped">{{ post.title }}</h5>
      </div>
      <!-- <div class="card-footer">
          <router-link
            :to="{ name: 'post.show', params: { post: post.id } }"
            class="btn btn-primary btn-sm btn-block"
            >Saber más...
          </router-link>
        </div> -->
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
  mounted(){
    console.log();
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