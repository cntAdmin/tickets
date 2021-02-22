<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Código Cliente</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Código Cliente
                  </div>
                </div>
                <input
                  type="text"
                  v-model="selected.customID"
                  class="form-control"
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Nombre Comercial</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Nombre Comercial
                  </div>
                </div>
                <input
                  type="text"
                  v-model="selected.comercialName"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Nombre Fiscal</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Nombre Fiscal
                  </div>
                </div>
                <input
                  type="text"
                  v-model="selected.fiscalName"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Tienda</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Tienda</div>
                </div>
                <input
                  type="text"
                  v-model="selected.shop"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Teléfono</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Teléfono</div>
                </div>
                <input
                  type="text"
                  v-model="selected.phone"
                  class="form-control"
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
    this.handleSubmit();
  },

  data() {
    return {
      selected: {
        page: 1,
        customID: "",
        comercialName: "",
        fiscalName: "",
        shop: "",
        phone: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("search", this.selected);
    },
  },
};
</script>

<style>
</style>