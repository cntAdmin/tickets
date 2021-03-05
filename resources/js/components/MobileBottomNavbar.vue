<template>
  <nav class="navbar navbar-dark bg-dark justify-content-center shadow-lg">
    <ul class="navbar-nav flex-row justify-content-between w-100 pb-2">
      <li class="nav-item">
        <div class="d-flex flex-column align-items-center">
        <button class="btn btn-link" title="No Vistas">
          <router-link
            :to="{ name: 'ticket.index', query: { status: 1 } }"
            class="text-white"
          >
            <i class="fas fa-file-alt fa-2x"></i>
            <span
              class="badge badge-pill badge-light text-dark position-absolute bottom-0 end-0 mt-2"
              >{{ newTickets }}</span
            >
          </router-link>
        </button>
          <sub class="text-white">No Vistas</sub>
        </div>
      </li>
      <li class="nav-item">
        <div class="d-flex flex-column align-items-center">
        <button class="btn btn-link" title="Incidencias">
          <router-link :to="{ name: 'ticket.index' }" class="text-white">
            <i class="fas fa-list-ul fa-2x"></i>
          </router-link>
        </button>
          <sub class="text-white">Incidencias</sub>
        </div>
      </li>

      <li class="nav-item">
        <div class="d-flex flex-column align-items-center">
          <button class="btn btn-link" title="Blog">
            <router-link :to="{ name: 'users-blog.index' }" class="text-white">
              <i class="fas fa-blog fa-2x"></i>
            </router-link>
          </button>
          <sub class="text-white">Blog</sub>
        </div>
      </li>
      <li class="nav-item">
        <div class="d-flex flex-column align-items-center">
          <button class="btn btn-link" title="Nueva Incidencia">
            <router-link :to="{ name: 'ticket.create' }" class="text-white">
              <i class="fas fa-plus-square fa-2x"></i>
            </router-link>
          </button>
          <sub class="text-white">Nueva</sub>
        </div>
      </li>
      <li class="nav-item">
        <div class="d-flex flex-column align-items-center">
          <button class="btn btn-link" title="Perfil">
            <router-link :to="{ name: 'profile.index', params: { user: user.id } }" class="text-white">
              <i class="fas fa-user fa-2x"></i>
            </router-link>
          </button>
          <sub class="text-white">Perfil</sub>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: ["newTickets", "user"],
};
</script>

<style>
</style>