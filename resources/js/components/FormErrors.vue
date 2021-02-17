<template>
  <div class="d-flex justify-content-center">
    <div v-for="(error, title) in errors" :key="title">
      <ul>
        <li v-for="(err, idx) in error" :key="idx">
          {{ err }}
        </li>
      </ul>
      <button type="button" class="close" @click="remove_errors()">
        <span>&times;</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["errors"]
}
</script>