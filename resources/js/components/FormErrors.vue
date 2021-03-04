<template>
    <div class="alert alert-dismissable alert-danger text-center">
      <button type="button" class="close" @click="$emit('close')">
        <span>&times;</span>
      </button>
      <div v-for="(error, title) in errors" :key="title">
        <ul class="list-unstyled">
          <li v-for="(err, idx) in error" :key="idx">
            {{ err }}
          </li>
        </ul>
      </div>
    </div>
</template>

<script>
export default {
  props: ["errors"],
  mounted() {
    // console.log(this.errors)
  }
};
</script>