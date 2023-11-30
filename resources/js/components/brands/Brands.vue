<template>
  <div class="mx-3">
    <div class="row justify-content-center">
      <card-counter
        title="Total"
        color="orange"
        :count="brands.data ? brands.total : 0"
        icon="clipboard-list"
        size="3"
      ></card-counter>
    </div>
    <div class="d-flex justify-content-center my-3">
      <button
        type="button"
        class="btn btn-secondary btn-sm text-uppercase btn-block"
        @click="is_new = true"
      >
        Crear Nueva Marca
      </button>
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

    <div v-show="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>

    <div
      v-show="deleted.status"
      class="alert alert-dismissable alert-danger my-3"
    >
      {{ deleted.msg }}
    </div>

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
      <brands-search-form @search="getBrands"></brands-search-form>
    </transition>

    <transition name="fade" v-if="is_new" mode="out-in">
      <brand-new
        @success="showSuccess"
        @error="showErrors"
        @close="closeAll()"
      ></brand-new>
    </transition>

    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <brand-edit
        :brand="brand"
        @close="closeAll"
        @success="showSuccess"
        @error="showErrors"
        @closeErrors="error.status = false"
      ></brand-edit>
    </transition>

    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="brands.data" mode="out-in">
      <brands-table
        :brands="brands"
        :searched="searched"
        @page="getBrands"
        @deleted="hasBeenDeleted"
        @edit="editBrand"
      >
      </brands-table>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      brands: [],
      brand: {},
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
        errors: [],
      },
      searched: {},
      searching: false,
      is_new: false,
      is_edit: false,
    };
  },
  mounted() {
    this.getBrands();
  },
  deactivated() {
    this.closeAll();
  },
  methods: {
    showSuccess(data) {
      this.success = {
        status: true,
        msg: data,
      };

      setTimeout(() => {
        this.closeAll();
        this.getBrands();
      }, 1500);
    },
    showErrors(data) {
      this.error = {
        status: true,
        errors: data,
      };
    },
    closeAll() {
      this.success.status = false;
      this.error.status = false;
      this.deleted.status = false;
      this.is_new = false;
      this.is_edit = false;
      this.searching = false;
    },
    editBrand(brand) {
      this.brand = brand;
      this.is_edit = true;
    },
    hasBeenDeleted(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.deleted = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.closeAll();
        this.getBrands();
      }, 2000);
    },
    getBrands(data) {
      this.searching = true;
      this.searched = data ? data : this.searched;
      let page = data && typeof data.page !== undefined ? data.page : 1;
      axios
        .get("/api/brand", {
          params: {
            page: page,
            name: data ? data.name : null,
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.searching = false;
            this.brands = res.data.brands;
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>
<style lang="css">
.fade-enter-active,
.fade-edit-form-leave-active {
  transition: opacity 0.3s;
}
.fade-edit-form-enter, .fade-edit-form-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
