<template>
  <div class="w-100">
    <div class="card shadow border-info">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Modelo</h3>
          </div>
          <div class="ml-auto">
            <button
              type="button"
              class="btn btn-sm btn-danger text-uppercase mb-3"
              @click="$emit('close')"
              aria-label="Close"
            >
            Cerrar
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline justify-content-center">
            <div class="form-group col-12 col-md-6">
              <label class="sr-only" for="name">Marca</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Marca</div>
                </div>
                <vue-select
                  :options="brands"
                  :value="carModel.brand.name"
                  class="col-9 px-0 w-100"
                  transition="vs__fade"
                  label="name"
                  itemid="id"
                  @input="setBrand"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-4">
              <label class="sr-only" for="name">nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">nombre</div>
                </div>
                <input
                  ref="name"
                  v-model="carModel.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control py-1'
                  "
                  type="text"
                  autofocus
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button
              class="btn btn-sm btn-block btn-info text-white text-uppercase font-weight-bold"
            >
              Guardar Cambios
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  deactivated() {
    // GLOBAL FUNCTION IN APP.JS
    this.resetFields();
  },
  props: ["brands", 'carModel'],
  data() {
    return {
      error: {
        errors: []
      }
    }
  },
  mounted() {
    this.$refs.name.focus();
  },
  methods: {
    setBrand(value) {
      this.carModel.brand_id = value ? value.id : null;
      this.carModel.brand.name = value ? value.name : null;
    },
    handleSubmit() {
      axios
        .put(`/api/car-model/${this.carModel.id}`, {
          brand_id: this.carModel.brand_id,
          name: this.carModel.name,
        })
        .then((res) => {
          console.log(res.data)
          if (res.data.success) {
            this.$emit("success", res.data.msg);
          } else if (res.data.error) {
            this.error.errors = res.data.errors;
            this.$emit("error", res.data.errors);
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>