<template>
  <form @submit.prevent="handleSubmit">
    <div class="d-flex justify-content-center flex-row flex-wrap mt-4">
      <div class="col-12 col-md-6 col-lg-4 mt-2 mt-md-0">
        <label class="sr-only" for="dateFrom">Buscar</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Buscar</div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="search.text"
            autofocus
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2 mt-md-0">
        <label class="sr-only" for="dateFrom">Marca</label>
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
      <div class="col-12 col-md-6 col-lg-4 mt-2 mt-md-0">
        <label class="sr-only" for="dateFrom">Modelo</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase py-1">Modelo</div>
          </div>
          <vue-select
            :options="carModels"
            class="col-9 px-0 w-100"
            transition="vs__fade"
            label="name"
            itemid="id"
            @input="setModel"
          >
            <div slot="no-options">No hay opciones con esta busqueda</div>
            <template slot="option" slot-scope="option">
              {{ option.name }}
            </template>
          </vue-select>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-3 mx-1">
      <button class="btn btn-sm btn-block btn-success text-uppercase">
        Buscar
      </button>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      search: {
        page: 1,
        text: "",
        brand_id: "",
        model_id: "",
      },
      brands: [],
      carModels: [],
    };
  },
  mounted() {
    this.getAllBrands();
  },
  methods: {
    handleSubmit() {
      this.$emit("search", this.search);
    },
    setModel(value) {
      this.search.model_id = value ? value.id : null;
    },
    setBrand(value) {
      this.search.brand_id = value ? value.id : null;
      if (this.search.brand_id !== null) {
        this.getAllModels();
      }
    },
    getAllBrands() {
      axios.get("/api/get_all_brands").then((res) => {
        this.brands = res.data.brands;
      });
    },
    getAllModels() {
      axios.get(`/api/brand/${this.search.brand_id}/model`).then((res) => {
        this.carModels = res.data.models;
      });
    },
  },
};
</script>

<style>
</style>