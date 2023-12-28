<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="carModels.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped table-sm shadow text-left">
          <thead class="thead-dark">
            <tr class="text-center text-uppercase">
              <th scope="col">#</th>
              <th scope="col">Nombre</th>
              <th scope="col">Modelos</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="text-center"
              v-for="model in carModels.data"
              :key="model.id"
            >
              <th scope="row">{{ model.id }}</th>
              <td>{{ model.brand.name }}</td>
              <td>{{ model.name }}</td>
              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-orange text-white"
                  @click="$emit('edit', model)"
                >
                  <i class="fa fa-edit"></i>
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-danger ml-2"
                  title="Eliminar Modelo"
                  @click="openDeleteModal(model)"
                >
                  <i class="fa fa-trash-alt"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <delete-modal
        title="Modelo"
        v-show="showModal"
        :data="carModel"
        @close="showModal = false"
        @getDeleted="getDeleted()"
      />
      <pagination
        :data="carModels"
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
  props: ["carModels", "searched"],
  data() {
    return {
      carModel: {},
      showModal: false,
    };
  },
  methods: {
    getDeleted() {
      axios
        .delete(`/api/car-model/${this.carModel.id}`)
        .then((res) => {
          if (res.data.success) {
            this.$emit("deleted", res.data.msg);
          } else if (res.data.error) {
            this.$emit("error", res.data.msg);
          }
        })
        .catch((err) => console.log(err));
    },
    openDeleteModal(carModel) {
      this.showModal = true;
      this.carModel = carModel;
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