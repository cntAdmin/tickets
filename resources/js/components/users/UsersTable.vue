<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="users.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped shadow">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Rol</th>
              <th scope="col">Cliente / Departamento</th>
              <th scope="col">Nombre</th>
              <th scope="col">Usuario</th>
              <th scope="col">Teléfono</th>
              <th scope="col">Email</th>
              <th class="text-center" scope="col">Incidencias</th>
              <th class="text-center" scope="col">Estado</th>
              <th class="text-center" scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in users.data" :key="u.id">
              <th scope="row" class="text-capitalize">
                {{ u.roles[0].name }}
              </th>
              <th v-if="u.customer">
                {{ u.customer.comercial_name }}
              </th>
              <th v-else-if="u.department">
                {{ u.department.name }}
              </th>
              <th v-else></th>
              <td>{{ u.fullname }}</td>
              <td>{{ u.username }}</td>
              <td>{{ u.phone }}</td>
              <td>{{ u.email }}</td>
              <td class="text-center">{{ u.tickets_count }}</td>
              <td>
                <button
                  v-if="u.is_active"
                  class="btn btn-sm btn-success btn-block"
                  :title="u.active_status"
                  disabled
                >
                  <i class="fa fa-check"></i>
                </button>
                <button
                  v-else
                  class="btn btn-sm btn-danger btn-block"
                  :title="u.active_status"
                  disabled
                >
                  <i class="fa fa-times"></i>
                </button>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <button
                    class="btn btn-sm btn-orange text-white mx-2"
                    @click="$emit('edit', u)"
                    title="Editar Cliente"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                  <button
                    class="btn btn-sm btn-danger mx-2"
                    @click="openDeleteModal(u)"
                    title="Eliminar Cliente"
                  >
                    <i class="fa fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
          <pagination
        :data="users"
        @pagination-change-page="emit_pagination"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
    <delete-modal v-show="showDeleteModal" title="Usuario" :data="user" @close="showDeleteModal = false" @getDeleted="getDeleted()"></delete-modal>
  </div>
</template>

<script>
export default {
  props: ["users", 'searched'],
  data() {
    return {
      user: {},
      showDeleteModal: false
    }
  },
  methods: {
    emit_pagination(page) {
      this.searched.page = page;
      this.$emit('page', this.searched)
    },
    openDeleteModal(u) {
      this.showDeleteModal = true;
      this.user = u;
    },
    getDeleted() {
      axios.delete('/api/user/' + this.user.id)
        .then( res => {
            // console.log(res.data)
            if(res.data.success) {
                this.$emit('deleted', res.data.msg)
            }
        });
    }
  }
};
</script>