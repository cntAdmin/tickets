<template>
  <div class="w-100">
    <div class="card shadow border-secondary">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Nuevo Modelo</h3>
          </div>
          <div class="ml-auto">
            <button
              type="button"
              class="close mb-3"
              @click="$emit('close')"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
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
                  v-model="selected.name"
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
              class="btn btn-sm btn-block btn-secondary text-uppercase font-weight-bold"
            >
              Crear Modelo
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
  props: ["brands"],
  data() {
    return {
      selected: {
        page: 1,
        brand_id: null,
        name: null,
      },
      error: {
        errors: []
      }
    };
  },
  mounted() {
    this.$refs.name.focus();
  },
  methods: {
    setBrand(value) {
      this.selected.brand_id = value ? value.id : null;
    },
    handleSubmit() {
      axios
        .post("/api/car-model", {
          brand_id: this.selected.brand_id,
          name: this.selected.name,
        })
        .then((res) => {
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

<style>
</style>