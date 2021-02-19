<template>
  <div class="w-100 mt-3">
    <div class="card shadow border-info">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Usuario</h3>
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
                  v-model="user.roles[0].id"
                  class="form-control"
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
              v-if="user.roles[0].id > 4"
            >
              <label class="sr-only" for="dateFrom">Cliente</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">
                    Cliente
                  </div>
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
                <select v-model="user.department_id" class="form-control">
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
                <input type="text" v-model="user.name" class="form-control" />
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
                  v-model="user.surname"
                  class="form-control"
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
                  v-model="user.username"
                  class="form-control"
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
                <input type="text" v-model="user.phone" class="form-control" />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Email</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Email</div>
                </div>
                <input type="email" v-model="user.email" class="form-control" />
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
                  v-model="user.password"
                  class="form-control"
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
                  v-model="user.password_confirmation"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">¿Activo?</label>
              <div class="input-group w-100">
                <span class="mr-3 mt-1">¿Activo?</span>
                <label class="switch">
                  <input type="checkbox" v-model="user.is_active" />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button
              class="btn btn-sm btn-block btn-info text-white text-uppercase font-weight-bold"
            >
              Guardar Usuario
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
  props: ["user"],
  data() {
    return {
      customers: [],
      roles: [],
      departments: [],
    };
  },
  mounted() {
    this.get_all_customers();
    this.get_all_roles();
    this.get_all_departments();
  },
  methods: {
    setCustomer(value) {
      this.user.customer_id = value ? value.id : null;
    },
    handleSubmit() {
      axios
        .put("/api/user/" + this.user.id, {
          customer_id: this.user.customer_id,
          department_id: this.user.department_id,
          name: this.user.name,
          surname: this.user.surname,
          username: this.user.username,
          phone: this.user.phone,
          email: this.user.email,
          password: this.user.password,
          password_confirmation: this.user.password_confirmation,
          is_active: this.user.is_active,
        })
        .then((res) => {
          console.log(res.data);
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
          } else if (res.data.error) {
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
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>