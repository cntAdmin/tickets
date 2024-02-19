<template>
  <form @submit.prevent="handleSubmit">
    <div class="d-flex flex-wrap justify-content-center">
      <div class="col-12 col-md-6 mt-2" v-if="is_admin">
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
      <div class="col-12 col-md-6 mt-2" v-if="is_admin">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Cliente</div>
          </div>
          <vue-select
            class="col-8 px-0 w-100"
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
      <div class="col-12 col-md-6 mt-2" v-else-if="!is_admin && user.roles[0].id <= 4">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Departamento</div>
          </div>
          <select 
            v-model="user.department_id"
            :class="[error.errors.department_id ? 'is-invalid' : ''] + ' form-control'"
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
      <div class="col-12 col-md-6 mt-2">
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
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Apellidos</div>
          </div>
          <input
            type="text"
            v-model="user.surname"
            :class="[error.errors.surname ? 'is-invalid' : ''] + ' form-control'"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Nombre de Usuario</div>
          </div>
          <input
            type="text"
            v-model="user.username"
            :class="[error.errors.username ? 'is-invalid' : ''] + ' form-control'"
            required
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Teléfono</div>
          </div>
          <input type="text" v-model="user.phone" class="form-control" />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Email</div>
          </div>
          <input
            type="email"
            v-model="user.email"
            :class="[error.errors.email ? 'is-invalid' : ''] + ' form-control'"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Contraseña</div>
          </div>
          <input
            type="password"
            v-model="user.password"
            :class="[error.errors.password ? 'is-invalid' : ''] + ' form-control'"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Rep. Contraseña</div>
          </div>
          <input
            type="password"
            v-model="user.password_confirmation"
            :class="[error.errors.password_confirmation ? 'is-invalid' : ''] +' form-control'"
          />
        </div>
      </div>
    </div>
    <div class="d-flex flex-wrap justify-content-center mt-3">
      <button class="btn btn-sm btn-block btn-orange text-white text-uppercase font-weight-bold">
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
      admin_roles: [ 1, 2]
    };
  },
  mounted() {
    if (this.type === "card") {
      this.size = "col-12";
    } else {
      this.size = "col-12 col-md-6 col-lg-6";
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