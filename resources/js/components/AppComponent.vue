<template>
  <transition name="fade" mode="out-in">
    <keep-alive>
      <router-view :key="$route.fullPath" :user_role="user_role" :user="user"></router-view>
    </keep-alive>
  </transition>
</template>

<script>
export default {
  props:['user_role', 'user'],
}
</script>
<style>
  .fade-enter-active,
  .fade-leave-active {
    transition-duration: 0.3s;
    transition-property: opacity;
    transition-timing-function: ease;
  }

  .fade-enter,
  .fade-leave-active {
    opacity: 0.0
  }
</style>