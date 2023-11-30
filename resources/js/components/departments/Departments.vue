<template>
  <div class="mx-3">
    <div class="row justify-content-center">
      <card-counter
        title="Total"
        color="orange"
        :count="departments.total ? departments.total : 0"
        icon="clipboard-list"
        size="3"
      />
    </div>
    <div class="d-flex justify-content-center mb-3">
      <div class="col-6">
        <button
          class="btn btn-sm btn-block btn-secondary text-uppercase shadow-lg font-weight-bold text-white"
          @click="departmentNew"
        >
          Crear Departamento
        </button>
      </div>
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

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
      <departments-search-form @search="getDepartments" />
    </transition>

    <transition name="fade" v-if="is_new" mode="out-in">
      <department-new
        @success="showSuccess"
        @error="showErrors"
        @close="closeAll"
      />
    </transition>

    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <department-edit
        :department="department"
        @close="closeAll"
        @success="showSuccess"
        @error="showErrors"
      />
    </transition>

    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>
    <transition v-else-if="departments.data" name="fade" mode="out-in">
      <departments-table
        :departments="departments"
        :searched="searched"
        @page="getDepartments"
        @edit="departmentEdit"
        @success="showSuccess"
        @error="showErrors"
        @close="closeAll()"
      />
    </transition>
  </div>
</template>

<script>
export default {
  inheritAttrs: "true",
  props: ["user_role"],
  data() {
    return {
      departments: [],
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
    };
  },
  deactivated() {
    this.closeAll();
  },
  mounted() {
    this.getDepartments();
  },
  methods: {
    getDepartments(data) {
      this.searching = true;
      this.searched = data ? data : this.searched;

      axios
        .get("/api/department", {
          params: {
            page: data ? data.page : 1,
            name: data ? data.name : null,
            code: data ? data.code : null,
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.searching = false;
            this.departments = res.data.departments;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    closeAll() {
      this.searching = false;
      this.is_new = false;
      this.is_edit = false;
      this.success.status = false;
      this.error.status = false;
      this.getDepartments();
    },
    departmentNew() {
      this.closeAll();
      this.is_new = true;
    },
    departmentEdit(data) {
      this.closeAll();
      this.is_edit = true;
      this.department = data;
    },
    showSuccess(data) {
      this.closeAll();
      this.success = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.getDepartments(data);
      }, 1500);
    },
    showErrors(data) {
      this.error = {
        status: true,
        errors: data,
      };
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
