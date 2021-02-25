<template>
  <nav
    class="navbar navbar-light bg-white shadow mt-1 h-100 col-2 flex-column overflow-auto position-fixed"
  >
    <div class="container">
      <button
        class="btn btn-block btn-link border-bottom d-block d-md-none"
        type="button"
        data-toggle="collapse"
        data-target="#sidebar_navbar"
        aria-controls="sidebar_navbar"
        aria-expanded="true"
        aria-label="Toggle navigation"
        title="Contraer/Expandir Menú"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        class="accordion navbar-collapse mt-2 d-none d-md-block"
        id="sidebar_navbar"
      >
        <ul class="navbar-nav">
          <div class="navbar-header">
            <h6 class="text-uppercase text-center">Panel</h6>
          </div>
          <div class="dropdown-divider"></div>
          <!-- TICKETS  -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100"
              data-toggle="collapse"
              data-target="#tickets_sidebar"
              aria-expanded="true"
              aria-controls="tickets_sidebar"
            >
              <div class="mr-auto">
                <button
                  class="btn btn-toolbar text-uppercase font-weight-bold w-100"
                >
                  Incidencias
                </button>
              </div>
              <div class="ml-auto">
                <span
                  class="navbar-toggler-icon"
                  type="button"
                  data-toggle="collapse"
                  data-target="#tickets_sidebar"
                  aria-expanded="true"
                  aria-controls="tickets_sidebar"
                >
                </span>
              </div>
            </div>
            <div class="collapse show" id="tickets_sidebar">
              <router-link
                class="btn btn-toolbar btn-block mt-2"
                :to="{ name: 'ticket.index' }"
              >
                <span>Todas las Incidencias</span>
              </router-link>
              <router-link
                v-for="status in ticket_statuses"
                :key="status.id"
                class="btn btn-toolbar btn-block mt-2"
                :to="{ name: 'ticket.index', query: { status: status.id } }"
                >Incidencias
                <span class="text-capitalize ml-1">{{
                  status.menu_name
                }}</span></router-link
              >
            </div>
          </li>
          <!-- FIN TICKETS  -->
          <li class="nav-item mt-2">
            <div class="shadow-sm">
              <router-link
                class="btn btn-toolbar text-uppercase font-weight-bold"
                :to="{ name: 'users-blog.index' }"
              >
                Blog
              </router-link>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  props: ["user_role"],
  data() {
      return {
          ticket_statuses: []
      }
  },
  mounted() {
      this.getTicketStatuses();
  },
  methods: {
    getTicketStatuses() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticket_statuses = res.data.ticket_statuses;
      });
    },
  },
};
</script>

<style>
</style>