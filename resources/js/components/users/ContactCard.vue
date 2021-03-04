<template>
    <div class="card">
      <div class="card-header text-uppercase">
        <h6>{{ name }}</h6>
      </div>
      <div class="card-body">
        <form action="#!" @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12">
              <label class="sr-only" for="dateFrom">Nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nombre</div>
                </div>
                <input
                  type="text"
                  v-model="contact.name"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Apellidos</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Apellidos</div>
                </div>
                <input
                  type="text"
                  v-model="contact.surname"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Nom. Usuario</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Nom. Usuario
                  </div>
                </div>
                <input
                  type="text"
                  v-model="contact.username"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Teléfono</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Teléfono</div>
                </div>
                <input
                  type="text"
                  v-model="contact.phone"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Email</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Email</div>
                </div>
                <input
                  type="email"
                  v-model="contact.email"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Contraseña</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Contraseña</div>
                </div>
                <input
                  type="password"
                  v-model="contact.password"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Rep. Contraseña</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Rep. Contraseña
                  </div>
                </div>
                <input
                  type="password"
                  v-model="contact.password_confirmation"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">¿Activo?</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">¿Activo?</div>
                </div>
                <!-- <span class="mr-3 mt-1">¿Activo?</span> -->
                <label class="switch ml-3">
                  <input type="checkbox" v-model="contact.is_active" />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>

            <div class="form-group col-12 mt-3">
              <button
                class="btn btn-sm btn-block btn-success text-uppercase font-weight-bold"
              >
                Actualizar Contacto
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
</template>

<script>
export default {
  props: ["contact"],
  data(){
      return {
          name: ''
      }
  },
  mounted() {
      this.name = this.contact.name;
  },
  methods: {
    handleSubmit() {
      axios
        .put(`/api/user/${this.contact.id}`, {
          customer_id: this.contact.customer_id,
          department_id: this.contact.department_id,
          name: this.contact.name,
          surname: this.contact.surname,
          username: this.contact.username,
          phone: this.contact.phone,
          email: this.contact.email,
          password: this.contact.password,
          password_confirmation: this.contact.password_confirmation,
          is_active: this.contact.is_active,
        })
        .then((res) => {
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
          } else if (res.data.error) {
            this.$emit("error", res.data.errors);
          }
          console.log("res", res.data);
        })
        .catch((err) => console.log(err));
      console.log("update user");
    },
  },
};
</script>

<style>
</style>