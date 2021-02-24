<template>
  <div class="w-100 mt-3">
    <div class="card shadow border-secondary">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Nuevo Usuario</h3>
          </div>
          <div class="ml-auto">
            <button
              type="button"
              class="close mb-3"
              @click="$emit('close')"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
      <!-- FIN CARD HEADER -->
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Rol</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Rol</div>
                </div>
                <select
                  v-model="selected.role_id"
                  :class="
                    [error.errors.role_id ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
                >
                  <option value="" disabled>Seleccione un rol</option>
                  <option
                    v-for="role in roles"
                    :value="role.id"
                    :key="role.id"
                    class="text-capitalize"
                  >
                    {{ role.name }}
                  </option>
                </select>
              </div>
            </div>
            <div
              class="form-group col-12 col-md-6 col-lg-4"
              v-if="selected.role_id > 4"
            >
              <label class="sr-only" for="dateFrom">Cliente</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Cliente</div>
                </div>
                <vue-select
                  class="col-9 px-0 w-100"
                  transition="vs__fade"
                  :options="customers"
                  label="comercial_name"
                  itemid="id"
                  @input="setCustomer"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.custom_id }} -
                    {{
                      option.comercial_name
                        ? option.comercial_name
                        : cs.fiscal_name
                    }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4" v-else>
              <label class="sr-only" for="dateFrom">Departamento</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Departamento
                  </div>
                </div>
                <select v-model="selected.department_id" class="form-control">
                  <option value="" disabled>Seleccione un Departamento</option>
                  <option
                    v-for="dp in departments"
                    :value="dp.id"
                    :key="dp.id"
                    class="text-capitalize"
                  >
                    {{ dp.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nombre</div>
                </div>
                <input
                  type="text"
                  v-model="selected.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Apellidos</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Apellidos</div>
                </div>
                <input
                  type="text"
                  v-model="selected.surname"
                  :class="
                    [error.errors.surname ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Nombre de Usuario</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Nombre de Usuario
                  </div>
                </div>
                <input
                  type="text"
                  v-model="selected.username"
                  :class="
                    [error.errors.username ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
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
                  :class="
                    [error.errors.phone ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Email</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Email</div>
                </div>
                <input
                  type="email"
                  v-model="selected.email"
                  :class="
                    [error.errors.email ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Contraseña</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Contraseña</div>
                </div>
                <input
                  type="password"
                  v-model="selected.password"
                  :class="
                    [error.errors.password ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Rep. Contraseña</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Rep. Contraseña
                  </div>
                </div>
                <input
                  type="password"
                  v-model="selected.password_confirmation"
                  :class="
                    [error.errors.password_confirmation ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button
              class="btn btn-sm btn-block btn-secondary text-uppercase font-weight-bold"
            >
              Nuevo Usuario
            </button>
          </div>
        </form>
      </div>
      <!-- FIN CARD BODY -->
    </div>
  </div>
</template>

<script>
export default {
  deactivated() {
    // GLOBAL FUNCTION IN APP.JS
    this.resetFields();
  },

  data() {
    return {
      customers: [],
      roles: [],
      departments: [],
      selected: {
        customer_id: "",
        department_id: "",
        name: "",
        surname: "",
        username: "",
        phone: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      error: {
        errors: []
      }
    };
  },
  mounted() {
    this.get_all_customers();
    this.get_all_roles();
    this.get_all_departments();
  },
  methods: {
    handleSubmit() {
      axios
        .post("/api/user", {
          customer_id: this.selected.customer_id,
          department_id: this.selected.department_id,
          name: this.selected.name,
          surname: this.selected.surname,
          username: this.selected.username,
          phone: this.selected.phone,
          email: this.selected.email,
          password: this.selected.password,
          password_confirmation: this.selected.password_confirmation,
        })
        .then((res) => {
          if (res.data.success) {
            this.$emit("created", res.data.msg);
          } else if (res.data.error) {
            this.error.errors = res.data.errors;
            this.$emit("error", res.data.errors);
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_all_customers() {
      axios.get("/api/get_all_customers").then((res) => {
        this.customers = res.data.customers;
      });
    },
    get_all_roles() {
      axios.get("/api/get_all_roles").then((res) => {
        this.roles = res.data.roles;
      });
    },
    get_all_departments() {
      axios.get("/api/get_all_departments").then((res) => {
        this.departments = res.data.departments;
      });
    },
  },
};
</script>

<style>
</style>