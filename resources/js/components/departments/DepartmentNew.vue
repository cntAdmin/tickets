<template>
  <div class="row my-3">
    <div class="card shadow w-100 border-secondary">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Nuevo Departamento</h3>
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
                  v-model="selected.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control py-1'
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
                  v-model="selected.code"
                  :class="
                    [error.errors.code ? 'is-invalid' : ''] + ' form-control py-1'
                  "
                  maxlength="5"
                  title="Máximo 5 caracteres"
                />
              </div>
            </div>
            <button class="btn btn-sm btn-secondary text-uppercase ml-3">
              <i class="fa fa-plus"></i
              ><span class="ml-3">Crear Departamento</span>
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

  data() {
    return {
      selected: {
        name: "",
        code: "",
      },
      error: {
        errors: []
      }
    };
  },
  methods: {
    handleSubmit() {
      if (this.selected.name.trim() === "" || this.selected.code.trim() === "")
        return;
      axios
        .post("/api/department", {
          name: this.selected.name,
          code: this.selected.code,
        })
        .then((res) => {
          if (res.data.success) {
            this.$emit("success", res.data.msg);
          } else if (res.data.error) {
            this.error.errors = res.data.errors;
            this.$emit("error", res.data.errors);
          }
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
  },
};
</script>

<style>
</style>