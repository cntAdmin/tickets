<template>
  <div class="row mt-3">
    <div class="card shadow w-100 border-info">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Marca</h3>
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
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
              <label class="sr-only" for="dateFrom">Nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nombre</div>
                </div>
                <input
                  ref="name"
                  type="text"
                  v-model="brand.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
                  autofocus
                />
              </div>
            </div>
            <button class="btn btn-info text-white text-uppercase ml-3">
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
  props: ["brand"],
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
    handleSubmit() {
        this.$emit('closeErrors');
      axios
        .put(`/api/brand/${this.brand.id}`, {
          name: this.brand.name,
        })
        .then((res) => {
          console.log(res.data);
          if (res.data.success) {
            this.$emit("success", res.data.msg);
          } else if (res.data.error) {
            if (res.data.errors) {
              this.error.errors = res.data.errors;
              this.$emit("error", res.data.errors);
            } else {
              this.$emit("error", res.data.msg);
            }
          }
        });
    },
  },
};
</script>

<style>
</style>