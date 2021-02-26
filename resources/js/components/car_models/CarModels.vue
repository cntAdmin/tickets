<template>
  <div class="mx-3">
    <div class="row justify-content-center">
      <card-counter
        title="Total"
        color="primary"
        :count="carModels.data ? carModels.total : 0"
        icon="clipboard-list"
        size="3"
      ></card-counter>
      <card-counter
        title="Marcas"
        color="secondary"
        :count="brands_count ? brands_count : 0"
        icon="clipboard-list"
        size="3"
      ></card-counter>
    </div>

    <div class="d-flex justify-content-center my-3">
      <button
        type="button"
        class="btn btn-secondary btn-sm text-uppercase btn-block font-weight-bold"
        @click="is_new = true"
      >
        Crear Nuevo Modelo
      </button>
    </div>
    <div v-show="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>

    <div v-show="success.status">
      <div class="alert alert-dismissable alert-success">
        {{ success.msg }}
        <button
          type="button"
          class="close mb-3"
          data-dismiss="alert"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
    <div
      v-show="deleted.status"
      class="alert alert-dismissable alert-danger my-3"
    >
      {{ deleted.msg }}
    </div>

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
      <car-models-search-form
        :brands="brands"
        @search="getCarModels"
      ></car-models-search-form>
    </transition>

    <transition name="fade" v-if="is_new" mode="out-in">
      <car-model-new
        :brands="brands"
        @success="showSuccess"
        @error="showErrors"
        @close="closeAll()"
      ></car-model-new>
    </transition>

    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <car-model-edit
        :carModel="carModel"
        :brands="brands"
        @close="closeAll"
        @success="showSuccess"
        @error="showErrors"
        @closeErrors="error.status = false"
      ></car-model-edit>
    </transition>

    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="carModels.data" mode="out-in">
      <car-models-table
        :carModels="carModels"
        :searched="searched"
        @page="getCarModels"
        @deleted="hasBeenDeleted"
        @edit="editCarModel"
      ></car-models-table>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      carModels: [],
      carModel: {},
      brands: [],
      searched: [],
      searching: false,
      is_new: false,
      is_edit: false,
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
      deleted: {
        status: false,
        msg: "",
      },
      brands_count: 0,
    };
  },
  mounted() {
    this.getAllBrands();
    this.getCounters();
    this.getCarModels();
  },
  methods: {
    getCounters() {
      axios.get("/api/get_car_models_counter").then((res) => {
        if (res.data.success) {
          this.brands_count = res.data.brands_count;
        }
      });
    },
    hasBeenDeleted(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.deleted = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.closeAll();
        this.getCarModels();
      }, 2000);
    },
    editCarModel(carModel) {
      this.closeAll();
      this.is_edit = true;
      this.carModel = carModel;
    },
    showSuccess(data) {
      this.success = {
        status: true,
        msg: data,
      };

      setTimeout(() => {
        this.closeAll();
        this.getCarModels();
      }, 1500);
    },
    showErrors(data) {
      this.error = {
        status: true,
        errors: data,
      };
    },
    getAllBrands() {
      axios.get("/api/get_all_brands").then((res) => {
        if (res.data.success) {
          this.brands = res.data.brands;
        }
      });
    },
    getCarModels(data) {
      this.searching = true;
      this.searched = data ? data : this.searched;
      let page = data && typeof data.page !== undefined ? data.page : 1;
      axios
        .get("/api/car-model", {
          params: {
            page: page,
            brand_id: data ? data.brand_id : null,
            name: data ? data.name : null,
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.searching = false;
            this.carModels = res.data.carModels;
          }
        });
    },
    closeAll() {
      this.is_new = false;
      this.is_edit = false;
      this.searching = false;
      this.success.status = false;
      this.deleted.status = false;
      this.error.status = false;
    },
  },
};
</script>