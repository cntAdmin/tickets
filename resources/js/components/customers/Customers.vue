<template>
  <div class="mx-3">
    <div class="row">
      <card-counter
        title="Total"
        color="primary"
        :count="customers ? customers.total : 0"
        icon="clipboard-list"
        size="4"
      />
      <card-counter
        title="Activos"
        color="info"
        :count="active"
        icon="times-circle"
        size="4"
      />
      <card-counter
        title="Inactivos"
        color="danger"
        :count="inactive"
        icon="envelope-open"
        size="4"
      />
    </div>
    <div class="d-flex justify-content-center my-3">
      <button
        class="btn btn-secondary btn-sm text-uppercase btn-block font-weight-bold"
        @click="is_new = true"
      >
        Crear Cliente
      </button>
    </div>

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
      <customers-search-form @search="getCustomers"></customers-search-form>
    </transition>

    <div
      class="alert alert-dismissable alert-danger my-3 text-center"
      v-if="deleted.status"
    >
      {{ deleted.msg }}
    </div>

    <div
      class="alert alert-dismissable alert-success my-3 text-center"
      v-if="success.status"
    >
      {{ success.msg }}
    </div>

    <div v-if="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>

    <transition name="fade" v-if="is_new" mode="out-in">
      <customer-new
        @close="closeAll()"
        @created="succeeded"
        @error="showErrors"
      ></customer-new>
    </transition>
    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <customer-edit
        :customer="customer"
        @close="closeAll()"
        @updated="succeeded"
        @error="showErrors"
      ></customer-edit>
    </transition>
    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="customers.data" mode="out-in">
      <customers-table
        :customers="customers"
        :searched="searched"
        @page="getCustomers"
        @deleted="succeeded"
        @edit="editCustomer"
      ></customers-table>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      customers: [],
      customer: {},
      active: 0,
      inactive: 0,
      searched: {},
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
    };
  },
  mounted() {
    this.getCustomers();
  },
  deactivated() {
    this.closeAll();
  },
  methods: {
    showErrors(data) {
      this.error = {
        status: true,
        errors: data,
      };
    },
    hasBeenDeleted(data) {
      this.closeAll();
      this.deleted = {
        status: true,
        msg: data,
      };
    },
    editCustomer(data) {
      this.closeAll();
      this.is_edit = true;
      this.customer = data;
    },
    succeeded(data) {
      this.closeAll();
      this.success = {
        status: true,
        msg: data,
      };

      setTimeout(() => {
        this.success = {
          status: false,
          msg: "",
        };
      }, 2000);
    },
    getCustomers(data) {
      this.getCounters();
      this.searching = true;
      this.searched = data ? data : this.searched;

      axios
        .get("/api/customer", {
          params: {
            page: data ? data.page : 1,
            custom_id: data ? data.customID : null,
            comercial_name: data ? data.comercialName : null,
            fiscal_name: data ? data.fiscalName : null,
            shop: data ? data.shop : null,
            phone: data ? data.phone : null,
          },
        })
        .then((res) => {
          this.customers = res.data.customers;
          this.searching = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    closeAll() {
      this.deleted.status = false;
      this.success.status = false;
      this.error.status = false;
      this.searching = false;
      this.is_new = false;
      this.is_edit = false;
      this.getCustomers();
    },
    getCounters() {
      axios.get("/api/get_customers_count").then((res) => {
        this.active = res.data.active;
        this.inactive = res.data.inactive;
      });
    },
  },
};
</script>

<style>
</style>