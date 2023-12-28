<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="brands.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped table-sm shadow text-left">
          <thead class="thead-dark">
            <tr class="text-center text-uppercase">
              <th scope="col">#</th>
              <th scope="col">Nombre</th>
              <th scope="col">Modelos</th>
              <th scope="col">Fecha Creación</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="text-center"
              v-for="brand in brands.data"
              :key="brand.id"
            >
              <th scope="row">{{ brand.id }}</th>
              <td class="text-left">{{ brand.name }}</td>
              <td>{{ brand.models_count }}</td>
              <td>{{ brand.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</td>
              <td>
                <div class="d-flex justify-content-center">
                  <div class="form-inline">
                    <button
                      type="button"
                      class="btn btn-sm btn-orange text-white"
                      @click="$emit('edit', brand)"
                    >
                      <i class="fa fa-edit"></i>
                    </button>
                    <button
                      type="button"
                      class="btn btn-sm btn-danger ml-2"
                      title="Eliminar Departamento"
                      @click="openDeleteModal(brand)"
                    >
                      <i class="fa fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <delete-modal
        title="Marca"
        v-show="showModal"
        :data="brand"
        @close="showModal = false"
        @getDeleted="getDeleted()"
      />
      <pagination
        :data="brands"
        :limit="3"
        @pagination-change-page="emit_pagination"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
    <div v-else class="mt-3 mx-3 shadow">
      <div class="alert alert-warning text-center">Haga una nueva búsqueda</div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["brands", "searched"],
  data() {
    return {
      brand: {},
      showModal: false,
    };
  },
  methods: {
    getDeleted() {
      this.showModal = false;
      axios
        .delete(`/api/brand/${this.brand.id}`)
        .then((res) => {
          if (res.data.success) {
            this.$emit("deleted", res.data.msg);
          } else if (res.data.error) {
            this.$emit("error", res.data.msg);
          }
        })
        .catch((err) => console.log(err));
    },
    openDeleteModal(brand) {
      this.showModal = true;
      this.brand = brand;
    },
    emit_pagination(page) {
      this.searched.page = page;
      this.$emit("page", this.searched);
    },
  },
};
</script>

<style>
</style>