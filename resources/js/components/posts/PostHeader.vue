<template>
  <div class="mb-2">
    <h3 class="col-12 text-center text-uppercase">{{ title }}</h3>
    <div class="row justify-content-center mt-4">
      <img v-if="image" :src="storageURL + image" :alt="title" class="img img-fluid">
    </div>
    <!-- AÑADIR IMAGEN DE FONDO -->
  </div>
</template>

<script>
export default {
  props: ["title", "image"],
  data() {
    return {
      storageURL: '/storage/'
    }
  }
};
</script>