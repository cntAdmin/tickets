<template>
  <div class="w-100 mt-3">
    <div class="card shadow border-dark">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Usuario</h3>
          </div>
          <div class="ml-auto">
            <button
              type="button"
              class="btn btn-sm btn-danger text-uppercase mb-3"
              @click="$emit('close')"
              aria-label="Close"
            >
              Cerrar
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- FIN CARD HEADER -->
      <div class="card-body">
        <div v-if="success.status">
          <div class="alert alert-dismissable alert-success">
            {{ success.msg }}
            <button type="button" class="close mb-3" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>

        <user-edit-form
          :user="user"
          :error="error"
          @error="$emit('error', error.errors)"
          @updated="userUpdated"
        ></user-edit-form>
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
      error: {
        status: false,
        errors: []
      },
      success: {
        status: false, 
        msg: null
      }
    };
  },
  mounted() {
    this.get_all_customers();
    this.get_all_roles();
    this.get_all_departments();
  },
  methods: {
    userUpdated(msg) {
      this.success = {
        status: true,
        msg: msg
      }
      setTimeout(() => {
        this.success = {
          status: false,
          msg: null
        }
        this.$emit('close');
      }, 1500);
    },
    setCustomer(value) {
      this.user.customer.comercial_name = value ? value.comercial_name : null;
      this.user.customer_id = value ? value.id : null;
    },
    handleSubmit() {
      axios
        .put("/api/user/" + this.user.id, {
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
          // console.log(res.data)
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
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