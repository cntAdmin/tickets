<template>
  <nav class="navbar navbar-dark bg-dark justify-content-center shadow-lg">
    <ul class="navbar-nav flex-row justify-content-between w-100">
      <li class="nav-item">
          <button class="btn btn-link" title="Incidencias Nuevas">
              <router-link
                :to="{ name: 'ticket.index', query: { status: 1 } }"
                class="text-white">
                <i class="fas fa-file-alt fa-2x"></i>
                <span class="badge badge-pill badge-light text-dark position-absolute bottom-0 end-0 mt-2">{{ newTickets }}</span>
              </router-link>
          </button>
      </li>
      <li class="nav-item">
          <button class="btn btn-link" title="Incidencias">
              <router-link 
                :to="{name: 'ticket.index'}"
              class="text-white">
                <i class="fas fa-list-ul fa-2x"></i>
              </router-link>
          </button>
      </li>

      <li class="nav-item">
          <button class="btn btn-link" title="Blog">
              <router-link :to="{name: 'users-blog.index'}" class="text-white">
                <i class="fas fa-blog fa-2x"></i>
              </router-link>
          </button>
      </li>
      <li class="nav-item">
          <button class="btn btn-link" title="Nueva Incidencia">
              <router-link :to="{name: 'ticket.create'}" class="text-white">
                <i class="fas fa-plus-square fa-2x"></i>
              </router-link>
          </button>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
    props: ["newTickets"]
};
</script>

<style>
</style>