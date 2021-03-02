<template>
  <transition name="fade" mode="out-in">
    <keep-alive>
      <router-view
        :key="$route.fullPath"
        :user_role="user_role"
        :user="user"
      ></router-view>
    </keep-alive>
  </transition>
</template>

<script>
export default {
  props: ["user_role", "user"],
};
</script>
<style>
.clamped {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.3s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
.text-shadow-lg {
  text-shadow: 4px 4px 2px #343a40;
}
.text-shadow-md {
  text-shadow: 3px 3px 5px #343a40;
}
.text-shadow-sm {
  text-shadow: 2px 1px 1px #343a40;
}
.text-shadow-danger-lg {
  text-shadow: 4px 4px 2px #e3342f;
}
.text-shadow-danger-md {
  text-shadow: 3px 3px 5px #e3342f;
}
.text-shadow-danger-sm {
  text-shadow: 2px 1px 1px #e3342f;
}
.text-shadow-light-lg {
  text-shadow: 4px 4px 2px #f8f9fa;
}
.text-shadow-light-md {
  text-shadow: 3px 3px 5px #f8f9fa;
}
.text-shadow-light-sm {
  text-shadow: 2px 1px 1px #f8f9fa;
}
</style>