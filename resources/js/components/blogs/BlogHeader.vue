<template>
  <div
    class="bg-blue-gradient row justify-content-center align-items-center w-100 shadow"
    style="height: 100px"
  >
    <h2 class="font-weight-bolder text-white text-shadow-md">{{ title }}</h2>
  </div>
</template>

<script>
export default {
  props: ["title"],
};
</script>