<template>
  <nav
    class="navbar navbar-light bg-blue-gradient h-100 overflow-hidden align-items-start"
  >
    <div class="">
      <!-- BOTON BURGER -->
      <div id="sidebar_navbar" class="navbar-collapse mt-2">
        <ul class="navbar-nav">
          <div class="navbar-header">
            <router-link
              :to="{ name: 'ticket.index' }"
              class="row justify-content-center"
            >
              <img src="/storage/aap_logo.jpeg" alt="AAP" width="50%" />
            </router-link>
          </div>
          <div class="dropdown-divider border-dark"></div>
          <!-- TICKETS  -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100 border border-light"
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
          <li class="nav-item mt-2">
            <div class="shadow-sm">
              <router-link
                id="blog"
                class="d-flex align-items-center shadow-sm w-100 border border-light text-decoration-none"
                :to="{ name: 'users-blog.index' }"
              >
                <h5
                  class="font-weight-bold text-uppercase m-3 text-shadow-light-sm text-dark"
                >
                  Blog
                </h5>
              </router-link>
            </div>
          </li>

          <li class="nav-item mt-2">
            <div class="shadow-sm">
              <router-link
                id="faqs"
                class="d-flex align-items-center shadow-sm w-100 border border-light text-decoration-none"
                :to="{ name: 'faqs.index' }"
              >
                <h5
                  class="font-weight-bold text-uppercase m-3 text-shadow-light-sm text-dark"
                >
                  Glosario de Incidencias
                </h5>
              </router-link>
            </div>
          </li>

          <div class="dropdown-divider"></div>
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100 border border-light"
              data-toggle="collapse"
              data-target="#user_info"
              aria-expanded="true"
              aria-controls="user_info"
            >
              <div class="mr-auto">
                <h5
                  class="font-weight-bold text-uppercase m-3 text-shadow-light-sm"
                >
                  {{ user.username }}
                </h5>
              </div>
              <div class="ml-auto">
                <span
                  class="navbar-toggler-icon mr-3"
                  type="button"
                  data-toggle="collapse"
                  data-target="#user_info"
                  aria-expanded="true"
                  aria-controls="user_info"
                >
                </span>
              </div>
            </div>
            <div class="collapse" id="user_info">
              <router-link
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                :to="{ name: 'profile.index', params: { user: user.id } }"
                >Mi Perfil
              </router-link>

              <button
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow-lg"
                onclick="event.preventDefault();  
                                document.getElementById('logout-form').submit();"
              >
                Salir
              </button>

              <form
                id="logout-form"
                action="/logout"
                method="POST"
                class="d-none"
              >
                <input type="hidden" name="_token" :value="csrf" />
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  props: ["user_role", "user"],
  data() {
    return {
      ticket_statuses: [],
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    };
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