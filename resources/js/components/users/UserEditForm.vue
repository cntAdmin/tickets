<template>
  <form @submit.prevent="handleSubmit">
    <div class="d-flex flex-wrap justify-content-center">
      <div :class="'mt-2 ' + size" v-if="is_admin">
        <label class="sr-only" for="dateFrom">Rol</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Rol</div>
          </div>
          <select v-model="user.roles[0].id" class="form-control" required>
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
      <div :class="'mt-2 ' + size" v-if="is_admin">
        <label class="sr-only" for="dateFrom">Cliente</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div
              class="input-group-text text-uppercase d-none d-lg-inline py-1"
            >
              Cliente
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-user-tie"></i>
            </div>
          </div>
          <vue-select
            class="col-9 px-0 w-100"
            transition="vs__fade"
            :options="customers"
            label="comercial_name"
            itemid="id"
            :value="user.customer.comercial_name"
            @input="setCustomer"
          >
            <div slot="no-options">No hay opciones con esta busqueda</div>
            <template slot="option" slot-scope="option">
              {{ option.custom_id }} -
              {{
                option.comercial_name ? option.comercial_name : cs.fiscal_name
              }}
            </template>
          </vue-select>
        </div>
      </div>
      <div :class="'mt-2 ' + size" v-else-if="!is_admin && user.roles[0].id < 4">
        <label class="sr-only" for="dateFrom">Departamento</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Departamento</div>
          </div>
          <select
            v-model="user.department_id"
            :class="
              [error.errors.department_id ? 'is-invalid' : ''] + ' form-control'
            "
          >
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
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Nombre</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Nombre</div>
          </div>
          <input
            type="text"
            v-model="user.name"
            :class="[error.errors.name ? 'is-invalid' : ''] + ' form-control'"
          />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Apellidos</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Apellidos</div>
          </div>
          <input
            type="text"
            v-model="user.surname"
            :class="
              [error.errors.surname ? 'is-invalid' : ''] + ' form-control'
            "
          />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Nombre de Usuario</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase d-none d-lg-inline">
              Nombre de Usuario
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-user-tag"></i>
            </div>
          </div>
          <input
            type="text"
            v-model="user.username"
            :class="
              [error.errors.username ? 'is-invalid' : ''] + ' form-control'
            "
            required
          />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Teléfono</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase d-none d-lg-inline">
              Teléfono
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-phone"></i>
            </div>
          </div>
          <input type="text" v-model="user.phone" class="form-control" />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Email</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase d-none d-lg-inline">
              Email
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-at"></i>
            </div>
          </div>
          <input
            type="email"
            v-model="user.email"
            :class="[error.errors.email ? 'is-invalid' : ''] + ' form-control'"
          />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Contraseña</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase d-none d-lg-inline">
              Contraseña
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-unlock-alt"></i>
            </div>
          </div>
          <input
            type="password"
            v-model="user.password"
            :class="
              [error.errors.password ? 'is-invalid' : ''] + ' form-control'
            "
          />
        </div>
      </div>
      <div :class="'mt-2 ' + size">
        <label class="sr-only" for="dateFrom">Rep. Contraseña</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase d-none d-lg-inline">
              Rep. Contraseña
            </div>
            <div class="input-group-text text-uppercase d-lg-none d-inline">
              <i class="fa fa-unlock-alt"></i><sup>2</sup>
            </div>
          </div>
          <input
            type="password"
            v-model="user.password_confirmation"
            :class="
              [error.errors.password_confirmation ? 'is-invalid' : ''] +
              ' form-control'
            "
          />
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
</template>

<script>
export default {
  props: ["user", "error", "type"],
  data() {
    return {
      customers: [],
      roles: [],
      departments: [],
      size: "",
      is_admin: false,
      admin_roles: [ 1, 2, 3, 4]
    };
  },
  mounted() {
    if (this.type === "card") {
      this.size = "col-12";
    } else {
      this.size = "col-12 col-md-6 col-lg-4";
    }
    this.is_admin = this.admin_roles.some(
      (role) => role === this.user.roles[0].id
    );

    this.get_all_customers();
    this.get_all_roles();
    this.get_all_departments();
  },
  methods: {
    setCustomer(value) {
      this.user.customer.comercial_name = value ? value.comercial_name : null;
      this.user.customer_id = value ? value.id : null;
    },

    handleSubmit() {
      axios
        .put(`/api/user/${this.user.id}`, {
          role_id: this.user.roles[0].id,
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
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
          } else if (res.data.error) {
            this.error.errors = res.data.errors;
            this.$emit("error", res.data.errors);
          }
        })
        .catch((err) => console.log(err));
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