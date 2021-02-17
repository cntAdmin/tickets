<template>
  <div class="mx-3">
    <div class="row justify-content-center">
      <card-counter
        title="Total"
        color="primary"
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
    <div v-show="is_success.status">
      <div class="alert alert-dismissable alert-success">
        {{ is_success.msg }}
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
    <div v-show="is_error.status">
      <div class="alert alert-dismissable alert-danger">
        <button
          type="button"
          class="close mb-3"
          data-dismiss="alert"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
        <ul v-for="(errors, idx) in is_error.msg" :key="idx">
          <li v-for="(error, idx2) in errors" :key="idx2">{{ error }}</li>
        </ul>
      </div>
    </div>

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
        <departments-search-form @search="getDepartments" />
    </transition>

    <transition name="fade" v-if="is_new" mode="out-in">
      <department-new @success="success" @error="error" @close="closeAll" />
    </transition>

    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <department-edit
        :department="department"
        @close="closeAll"
        @success="success"
        @error="error"
      />
    </transition>

    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>
    <transition
      name="fade"
      v-else-if="departments.data "
      mode="out-in"
    >
      <departments-table
        :departments="departments"
        @page="getDepartments"
        :searched="searched"
        @edit="departmentEdit"
        @success="success"
        @error="error"
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
      is_success: {
        status: false,
        msg: "",
      },
      is_error: {
        status: false,
        msg: "",
      },
    };
  },
  mounted() {
    this.getDepartments();
  },
  methods: {
    getDepartments(data) {
      this.closeAll();
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
      this.is_success.status = false;
      this.is_error.status = false;
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
    success(data) {
      this.closeAll();
      this.is_success.status = true;
      this.is_success.msg = data;
      setTimeout(() => {
        this.getDepartments(data);
      }, 1500);
    },
    error(data) {
      this.is_error.status = true;
      this.is_error.msg = data;
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