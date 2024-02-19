<template>
  <nav class="navbar navbar-light bg-orange-gradient h-100 align-items-start">
    <div class="container">
      <div class="accordion navbar-collapse mt-2 d-none d-md-block" id="sidebar_navbar">
        <ul class="navbar-nav">
          <div class="navbar-header">
            <div class="d-flex flex-column justify-content-center align-items-center">
              <router-link :to="{ name: 'ticket.index' }" class="row justify-content-center">
                <img src="/storage/logo-andel.svg" alt="AAP" width="200px"/>
              </router-link>
              <div class="row justify-content-center my-3 w-100">
                <router-link
                  class="btn btn-light btn-block text-uppercase shadow font-weight-bold"
                  :to="{ name: 'profile.index', params: { user: user.id } }"
                  >Hola {{ user.name }}
                </router-link>
              </div>
            </div>
          </div>
          <!-- <div class="dropdown-divider border-dark"></div> -->
          
          <!-- DASHBOARD  -->
          <li class="nav-item shadow-sm mt-2">
            <router-link id="calls" class="text-decoration-none nav-link" :to="{ name: 'dashboard.index' }">
              <h5 class="text-dark font-weight-bold text-uppercase my-1 ml-3">Dashboard</h5>
            </router-link>
          </li>

          <!-- ADMINISTRACIÓN -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100 collapsed"
              data-toggle="collapse"
              data-target="#admin"
              aria-expanded="false"
              aria-controls="admin"
            >
              <div class="mr-auto">
                <h5 class="text-dark font-weight-bold text-uppercase m-3">Administración</h5>
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
            <div class="collapse" id="admin" data-parent="#sidebar_navbar">
              <!-- <li class="nav-item"> -->
                <router-link id="brands" class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'brand.index' }">
                  <span class="font-weight-bold">Marcas</span>
                </router-link>
              <!-- </li> -->
              <router-link id="car-model" class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'car_model.index' }">
                <span class="font-weight-bold">Modelos</span>
              </router-link>
              <router-link id="departments" class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'department.index' }">
                <span class="font-weight-bold">Departamentos</span>
              </router-link>
              <router-link class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'customer.index' }">
                <span class="font-weight-bold">Clientes</span>
              </router-link>
              <router-link class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'user.index' }">
                <span class="font-weight-bold">Usuarios</span>
              </router-link>
              <router-link class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'post.index' }">
                <span class="font-weight-bold">Blog</span>
              </router-link>
            </div>
          </li>


          <!-- TICKETS  -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100"
              data-toggle="collapse"
              data-target="#tickets_sidebar"
              aria-expanded="true"
              aria-controls="tickets_sidebar"
              data-parent="sidebar_navbar"
            >
              <div class="mr-auto">
                <h5 class="text-dark font-weight-bold text-uppercase m-3">Incidencias</h5>
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
            <div class="collapse show" id="tickets_sidebar" data-parent="#sidebar_navbar">
              <router-link class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow" :to="{ name: 'ticket.index' }">
                <span>Todas las Incidencias</span>
              </router-link>
              <router-link
                v-for="status in ticket_statuses" :key="status.id"
                :class=" status.id == 3 ? 'd-none' : 'btn btn-dark btn-block mt-2 text-light text-uppercase shadow'"
                :to="{ name: 'ticket.index', query: { status: status.id } }"
              >
                <span class="ml-1">
                  {{ status.menu_name }}<span class="ml-2 badge badge-danger" v-if="status.id == 1">{{ answered }}</span>
                </span>
              </router-link>
            </div>
          </li>
          

          <!-- LLAMADAS  -->
          <!-- <li class="nav-item shadow-sm mt-2">
            <router-link id="calls" class="text-decoration-none nav-link" :to="{ name: 'call.index' }">
              <h5 class="text-dark font-weight-bold text-uppercase mx-3 text-shadow-light-sm">
                Llamadas
              </h5>
            </router-link>
          </li> -->


          <!-- BLOG  -->
          <li class="nav-item shadow-sm mt-2">
            <router-link id="blog" class="text-decoration-none nav-link" :to="{ name: 'users-blog.index' }">
              <h5 class="text-dark font-weight-bold text-uppercase my-1 ml-3">Blog</h5>
            </router-link>
          </li>


          <!-- GLOSARIO DE INCIDENCIAS  -->
          <li class="nav-item shadow-sm mt-2">
            <!-- <div class="shadow-sm"> -->
              <router-link id="faqs" class="text-decoration-none nav-link" :to="{ name: 'faqs.index' }">
                <h5 class="text-dark font-weight-bold text-uppercase my-1 ml-3">
                  Glosario de Incidencias
                </h5>
              </router-link>
            <!-- </div> -->
          </li>


          <!-- MEDIA  -->
          <li class="nav-item shadow-sm mt-2">
            <router-link id="file_manager" class="text-decoration-none nav-link" :to="{ name: 'file_manager.index' }">
              <h5 class="text-dark font-weight-bold text-uppercase my-1 ml-3">Media</h5>
            </router-link>
          </li>

          <!-- MI PERFIL -->
          <li class="nav-item w-100">
            <div
              class="d-flex align-items-center shadow-sm w-100 collapsed"
              data-toggle="collapse"
              data-target="#user_info"
              aria-expanded="true"
              aria-controls="user_info"
            >
              <div class="mr-auto">
                <h5 class="text-dark font-weight-bold text-uppercase m-3">Mi Perfil</h5>
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
            <div class="collapse" id="user_info" data-parent="#sidebar_navbar">
              <router-link
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow"
                :to="{ name: 'profile.index', params: { user: user.id } }"
              >
                Mi Perfil
              </router-link>
              <button
                class="btn btn-dark text-light btn-block mt-2 text-uppercase shadow"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              >
                Logout
              </button>

              <form id="logout-form" action="/logout" method="POST" class="d-none">
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
      answered: 0,
    };
  },
  beforeMount() {
    this.getTicketStatuses();
    this.getAnswered();
  },
  mounted() {
    this.$nextTick(function () {
      window.setInterval(() => {
        this.getAnswered();
      }, 60000);
    });
  },
  methods: {
    getTicketStatuses() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticket_statuses = res.data.ticket_statuses;
      });
    },
    getAnswered() {
      // axios.get("/api/get_answered").then((res) => {
      axios.get("/api/get_nuevos_tickets").then((res) => {
        this.answered = res.data.answered;
      });
    },
  },
};
</script>