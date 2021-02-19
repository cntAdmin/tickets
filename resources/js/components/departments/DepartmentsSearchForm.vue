<template>
  <div class="w-100">
    <div class="card shadow">
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
                  class="form-control"
                  title="Minimo 3 caracteres"
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
                  class="form-control"
                  title="Minimo 3 caracteres"
                  autofocus
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-block btn-success text-uppercase">
              Buscar
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
        page: 1,
        name: "",
        code: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.selected.page = 1;
      this.$emit("search", this.selected);
    },
  },
};
</script>

<style>
</style>