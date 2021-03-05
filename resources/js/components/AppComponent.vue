<template>
  <div class="d-flex flex-row">
    <!-- DESKTOP SIDEBAR  -->
    <div
      class="d-none d-xl-block position-fixed vh-100 col-2 m-0 p-0 flex-column shadow"
    >
      <sidebar v-if="!is_admin" :user_role="user_role" :user="user"></sidebar>
      <admin-sidebar v-else :user_role="user_role" :user="user"></admin-sidebar>
    </div>
    <!-- MAIN CONTEN -->
    <div class="col-xl-10 col-12 ml-auto">
      <main>
        <transition name="fade" mode="out-in">
          <keep-alive>
            <router-view
              :key="$route.fullPath"
              :user_role="user_role"
              :user="user"
              @getNewTickets="get_new_tickets()"
            ></router-view>
          </keep-alive>
        </transition>
      </main>
      <!-- MOBILE SIDEBAR -->
      <div class="fixed-bottom d-xl-none d-block">
        <mobile-bottom-navbar :newTickets="newTickets" :user="user"></mobile-bottom-navbar>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user_role", "user"],
  data() {
    return {
      newTickets: 0,
      is_admin: false,
      admin_roles: [1, 2, 3, 4],
    };
  },
  mounted() {
    this.is_admin = this.admin_roles.some(
      (role) => role === this.user.roles[0].id
    );
  },
  methods: {
    get_new_tickets() {
      axios
        .get("/api/get_ticket_counters")
        .then((res) => {
          this.newTickets = res.data.newTickets;
        })
        .catch((err) => console.log(err));
    },
  },
  watch: {
    $route(to, from) {
      this.get_new_tickets();
    },
  },
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