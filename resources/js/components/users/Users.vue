<template>
  <div class="mx-3">
    <div class="row">
      <card-counter
        title="Total"
        color="primary"
        :count="users ? users.total : 0"
        icon="clipboard-list"
        size="4"
      />
      <card-counter
        title="Admin"
        color="success"
        :count="admin_count"
        icon="times-circle"
        size="4"
      />
      <card-counter
        title="Agentes"
        color="info"
        :count="staff_count"
        icon="envelope-open"
        size="4"
      />
      <card-counter
        title="Departamento"
        color="secondary"
        :count="department_count"
        icon="clipboard-list"
        size="4"
      />
      <card-counter
        title="Clientes"
        color="dark"
        :count="customer_count"
        icon="times-circle"
        size="4"
      />
      <card-counter
        title="Usuarios"
        color="danger"
        :count="contact_count"
        icon="envelope-open"
        size="4"
      />
    </div>
    <div class="d-flex justify-content-center my-3">
      <button
        class="btn btn-secondary btn-sm text-uppercase btn-block font-weight-bold"
        @click="is_new = true"
      >
        Crear Usuario
      </button>
    </div>

    <transition name="fade" v-if="!is_new && !is_edit" mode="out-in">
      <users-search-form @search="getUsers"></users-search-form>
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
      <user-new
        @close="closeAll()"
        @created="succeeded"
        @error="showErrors"
        @closeErrors="error.status = false"
      ></user-new>
    </transition>
    <transition name="fade" v-else-if="is_edit" mode="out-in">
      <user-edit
        :user="user"
        @close="closeAll()"
        @updated="succeeded"
        @error="showErrors"
      ></user-edit>
    </transition>
    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="users.data" mode="out-in">
      <users-table
        :users="users"
        @page="getUsers"
        :searched="searched"
        @deleted="succeeded"
        @edit="editUser"
      ></users-table>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      user: {},
      searched: {},
      success: {
        status: false,
        msg: "",
      },
      deleted: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
      admin_count: 0,
      staff_count: 0,
      department_count: 0,
      customer_count: 0,
      contact_count: 0,
      is_new: false,
      is_edit: false,
      searching: false,
    };
  },
  deactivated() {
    this.closeAll();
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    showErrors(data) {
      this.error = {
        status: true,
        errors: data,
      };
    },
    succeeded(data) {
      this.success.status = true;
      this.success.msg = data;

      setTimeout(() => {
        this.closeAll();
      }, 1500);
    },
    editUser(data) {
      this.closeAll();
      this.is_edit = true;
      this.user = data;
    },
    getUsers(data) {
      this.getCounters();
      this.searching = true;
      this.searched = data ? data : this.searched;

      axios
        .get("/api/user", {
          params: {
            page: data ? data.page : 1,
            name: data ? data.name : null,
            surname: data ? data.surname : null,
            username: data ? data.username : null,
            email: data ? data.email : null,
            role_id: data ? data.role_id : null,
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.users = res.data.users;
            this.searching = false;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCounters() {
      axios.get("/api/get_users_counters").then((res) => {
        this.admin_count = res.data.admin_count;
        this.staff_count = res.data.staff_count;
        this.department_count = res.data.department_count;
        this.customer_count = res.data.customer_count;
        this.contact_count = res.data.contact_count;
      });
    },
    closeAll() {
      this.is_new = false;
      this.is_edit = false;
      this.searching = false;
      this.success.status = false;
      this.deleted.status = false;
      this.error.status = false;
      this.getUsers();
    },
  },
};
</script>

<style>
</style>