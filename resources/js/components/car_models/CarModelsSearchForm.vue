<template>
  <div class="w-100">
    <div class="card shadow">
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
                  class="col-9 px-0 w-100"
                  transition="vs__fade"
                  :options="brands"
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
                  v-model="search.name"
                  class="form-control py-1"
                  type="text"
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
  props:["brands"],
  data() {
    return {
      search: {
        page: 1,
        brand_id: null,
        name: null,
      },
    };
  },
  mounted() {
    this.$refs.name.focus();
  },
  methods: {
    setBrand(value) {
        this.search.brand_id = value ? value.id : null;
    },
    handleSubmit() {
      this.search.page = 1;
      this.$emit("search", this.search);
    },
  },
};
</script>

<style>
</style>