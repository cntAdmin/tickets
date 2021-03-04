<template>
  <nav
    class="navbar navbar-light bg-blue-gradient h-100 overflow-hidden align-items-start"
  >
    <div class="container">
      <div
        class="accordion navbar-collapse mt-2 d-none d-md-block"
        id="sidebar_navbar"
      >
        <ul class="navbar-nav">
          <div class="navbar-header">
            <router-link :to="{ name: 'ticket.index' }" class="row justify-content-center">
              <img src="/storage/aap_logo.jpeg" alt="AAP" width="50%" />
            </router-link>
          </div>
          <div class="dropdown-divider border-dark"></div>
          <!-- ADMINISTRACIÓN -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100"
              data-toggle="collapse"
              data-target="#admin"
              aria-expanded="false"
              aria-controls="admin"
            >
              <div class="mr-auto">
                <h5
                  class="font-weight-bold text-uppercase m-3 text-shadow-light-sm"
                >
                  Administración
                </h5>
              </div>
              <div class="ml-auto">
                <span
                  class="navbar-toggler-icon mr-3"
                  type="button"
                  data-toggle="collapse"
                  data-target="#admin"
                  aria-expanded="true"
                  aria-controls="admin"
                ></span>
              </div>
            </div>
            <div class="collapse" id="admin">
              <li class="nav-item">
                <router-link
                  id="brands"
                  class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                  :to="{ name: 'brand.index' }"
                >
                  <span class="font-weight-bold">Marcas</span>
                </router-link>
              </li>
              <router-link
                id="car-model"
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'car_model.index' }"
              >
                <span class="font-weight-bold">Modelos</span>
              </router-link>
              <router-link
                id="departments"
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'department.index' }"
              >
                <span class="font-weight-bold">Departamentos</span>
              </router-link>
              <router-link
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'customer.index' }"
              >
                <span class="font-weight-bold">Clientes</span>
              </router-link>
              <router-link
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'user.index' }"
              >
                <span class="font-weight-bold">Usuarios / Contactos</span>
              </router-link>
              <router-link
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'post.index' }"
              >
                <span class="font-weight-bold">Blog</span>
              </router-link>
            </div>
          </li>
          <!-- FIN ADMINISTRACIÓN -->
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
                <h5
                  class="font-weight-bold text-uppercase m-3 text-shadow-light-sm"
                >
                  Incidencias
                </h5>
              </div>
              <div class="ml-auto">
                <span
                  class="navbar-toggler-icon mr-3"
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
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'ticket.index' }"
              >
                <span>Todas las Incidencias</span>
              </router-link>
              <router-link
                v-for="status in ticket_statuses"
                :key="status.id"
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'ticket.index', query: { status: status.id } }"
                >Incidencias
                <span class="ml-1">{{ status.menu_name }}</span></router-link
              >
            </div>
          </li>
          <!-- FIN TICKETS  -->
          <li class="nav-item shadow-sm mt-2">
            <router-link
              id="calls"
              class="text-decoration-none nav-link"
              :to="{ name: 'call.index' }"
            >
              <h5
                class="text-dark font-weight-bold text-uppercase mx-3 text-shadow-light-sm"
              >
                Llamadas
              </h5>
            </router-link>
          </li>
          <li class="nav-item shadow-sm mt-2">
            <router-link
              id="blog"
              class="text-decoration-none nav-link"
              :to="{ name: 'users-blog.index' }"
            >
              <h5
                class="text-dark font-weight-bold text-uppercase mx-3 text-shadow-light-sm"
              >
                Blog
              </h5>
            </router-link>
          </li>
          <li class="nav-item shadow-sm mt-2">
            <router-link
              id="file_manager"
              class="text-decoration-none nav-link"
              :to="{ name: 'file_manager.index' }"
            >
              <h5
                class="text-dark font-weight-bold text-uppercase mx-3 text-shadow-light-sm"
              >
                Media
              </h5>
            </router-link>
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
      ticket_statuses: [],
    };
  },
  beforeMount() {
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
.bg-blue-gradient {
  background: rgb(33, 112, 184);
  background: linear-gradient(
    0deg,
    rgba(33, 112, 184, 1) 0%,
    rgba(0, 91, 255, 1) 0%,
    rgba(208, 252, 255, 1) 100%
  );
}
.btn-dark.router-link-exact-active.router-link-active {
  background-color: white;
  color: black !important;
}
.text-decoration-none.router-link-exact-active.router-link-active {
  /* background-color: orange; */
  color: white !important;
  text-decoration: underline !important;
  text-decoration-color: white !important;
}
</style>