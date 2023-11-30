<template>
  <div class="row mt-3 mx-3">
    <div class="card shadow w-100 border-info">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Departamento</h3>
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
                  type="text"
                  v-model="department.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control'
                  "
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
              <label class="sr-only" for="dateFrom">Código</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Código</div>
                </div>
                <input
                  type="text"
                  v-model="department.code"
                  :class="
                    [error.errors.code ? 'is-invalid' : ''] + ' form-control'
                  "
                  maxlength="5"
                  title="Máximo 5 caracteres"
                />
              </div>
            </div>
            <button class="btn btn-sm btn-orange text-white text-uppercase ml-3">
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
  props: ["department"],
  data() {
    return {
      error: {
        errors: []
      }
    }
  },
  methods: {
    handleSubmit() {
      axios
        .put(`/api/department/${this.department.id}`, {
          name: this.department.name,
          code: this.department.code,
        })
        .then((res) => {
          if (res.data.success) {
            this.$emit("success", res.data.msg);
          } else if (res.data.error) {
            if (Array.isArray(res.data.errors)) {
              this.error.errors = res.data.errors;
              this.$emit("error", res.data.errors);
            } else {
              this.$emit("error", res.data.msg);
            }
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style>
</style>