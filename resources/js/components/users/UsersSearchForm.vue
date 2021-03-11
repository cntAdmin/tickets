<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-3">
              <label class="sr-only" for="dateFrom">Rol</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Rol</div>
                </div>
                <select v-model="selected.role_id" class="form-control">
                  <option value="">-- TODOS --</option>
                  <option :value="rol.id" v-for="rol in roles" :key="rol.id">{{ rol.name }}</option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-3">
              <label class="sr-only" for="dateFrom">Nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nombre</div>
                </div>
                <input
                  type="text"
                  v-model="selected.name"
                  class="form-control"
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-3">
              <label class="sr-only" for="dateFrom">Usuario</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Usuario
                  </div>
                </div>
                <input
                  type="text"
                  v-model="selected.username"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-3">
              <label class="sr-only" for="dateFrom">Email</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Email</div>
                </div>
                <input
                  type="text"
                  v-model="selected.email"
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
        role_id: "",
        name: "",
        surname: "",
        username: "",
        email: "",
      },
      roles: []
    };
  },
  activated() {
    this.get_all_roles();
  },
  methods: {
    get_all_roles() {
      axios.get('/api/get_all_roles')
      .then( res => {
        this.roles = res.data.roles;
      })
    },
    handleSubmit() {
      this.$emit("search", this.selected);
    },
  },
};
</script>

<style>
</style>