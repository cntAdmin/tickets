<template>
  <div class="d-flex flex-wrap justify-content-center mt-2">
    <div v-if="$screen.breakpoint === 'xs'" class="col-12 mb-4">
      <button
        class="btn btn-danger btn-block mt-2 text-uppercase shadow-lg"
        onclick="event.preventDefault();  
                                document.getElementById('logout-form').submit();"
      >
        Salir
      </button>

      <form id="logout-form" action="/logout" method="POST" class="d-none">
        <input type="hidden" name="_token" :value="csrf" />
      </form>
    </div>
    <div class="col-12">
      <ul class="nav nav-tabs nav-fill" id="my_profile_tabs" role="tablist">
        <li class="nav-item">
          <a
            class="nav-link active"
            id="profile-tab"
            data-toggle="tab"
            href="#profile"
            role="tab"
            aria-controls="profile"
            aria-selected="true"
          >
            Mi Perfil
          </a>
        </li>
        <li class="nav-item" v-if="user_role == 5">
          <a
            class="nav-link"
            id="contact-tab"
            data-toggle="tab"
            href="#contact"
            role="tab"
            aria-controls="contact"
            aria-selected="false"
          >
            Mis Usuarios
          </a>
        </li>
      </ul>
    </div>
    <div class="col-12 tab-content">
      <div
        class="tab-pane fade show active"
        id="profile"
        role="tabpanel"
        aria-labelledby="profile-tab"
      >
        <!-- SI ES PERFIL CUSTOMER -->
        <div
          v-if="user_role == 5"
          class="d-flex flex-wrap justify-content-start mt-3 pt-3"
        >
          <div v-if="customerSuccess.status" class="w-100">
            <div class="alert alert-dismissable alert-success text-center">
              {{ customerSuccess.msg }}
              <button
                type="button"
                class="close mb-3"
                data-dismiss="alert"
                aria-label="Close"
                @click="customerSuccess.status = false"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>

          <div v-if="customerError.status" class="w-100">
            <form-errors
              :errors="customerError.errors"
              @close="customerError.status = false"
            ></form-errors>
          </div>

          <form @submit.prevent="handleCustomerSubmit">
            <h5 class="font-weight-bold text-uppercase mt-3">
              Datos de Cliente
            </h5>

            <div class="form-inline">
              <div class="form-group col-12 col-md-6 col-lg-4">
                <label class="sr-only" for="dateFrom">Codigo Cliente</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Codigo Cliente
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.custom_id"
                    class="form-control"
                    disabled
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4">
                <label class="sr-only" for="dateFrom">CIF</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">CIF</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.cif"
                    :class="
                      [customerError.errors.cif ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4">
                <label class="sr-only" for="dateFrom">Nombre Fiscal</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Nombre Fiscal
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.fiscal_name"
                    :class="
                      [customerError.errors.fiscal_name ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                    autofocus
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Nombre Comercial</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Nombre Comercial
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.comercial_name"
                    :class="
                      [
                        customerError.errors.comercial_name ? 'is-invalid' : '',
                      ] + ' form-control'
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
                    type="text"
                    v-model="user.customer.email"
                    :class="
                      [customerError.errors.email ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Tienda</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">Tienda</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.shop"
                    :class="
                      [customerError.errors.shop ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Teléfono 1</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Teléfono 1
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.phone_1"
                    :class="
                      [customerError.errors.phone_1 ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Teléfono 2</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Teléfono 2
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.phone_2"
                    :class="
                      [customerError.errors.phone_2 ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Teléfono 3</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">
                      Teléfono 3
                    </div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.phone_3"
                    :class="
                      [customerError.errors.phone_3 ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Dirección</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">Dirección</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.street"
                    :class="
                      [customerError.errors.street ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Ciudad</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">Ciudad</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.city"
                    :class="
                      [customerError.errors.city ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">Provincia</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">Provincia</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.province"
                    :class="
                      [customerError.errors.province ? 'is-invalid' : ''] +
                      ' form-control'
                    "
                  />
                </div>
              </div>
              <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                <label class="sr-only" for="dateFrom">País</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <div class="input-group-text text-uppercase">País</div>
                  </div>
                  <input
                    type="text"
                    v-model="user.customer.country"
                    :class="
                      [customerError.errors.country ? 'is-invalid' : ''] +
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
                Actualizar Cliente
              </button>
            </div>
          <div class="dropdown-divider my-3"></div>
          </form>

          <h5 class="font-weight-bold text-uppercase mt-3">Datos de Usuario</h5>
          <user-edit-form 
            v-if="user.id"
            :user="user"
            :error="contactError"
            @updated="showCustomerSuccess"
            @error="showCustomerErrors"
            />
        </div>
        <!-- SI EL USUARIO NO ES CUSTOMER SOLO MUESTRA SUS DATOS -->
        <div v-else class="d-flex flex-wrap justify-content-start mt-3 pt-3">
          <div class="d-flex flex-wrap justify-content-start" v-if="user.id">
            <!-- FIN CARD HEADER -->
            <div class="card">
              <div class="card-body">
                <user-edit-form
                  v-if="user.id"
                  :user="user"
                  :error="contactError"
                  @error="showCustomerErrors"
                  @updated="showCustomerSuccess"
                ></user-edit-form>
              </div>
            </div>
            <!-- FIN CARD BODY -->
          </div>
        </div>
      </div>

      <div
        class="tab-pane fade"
        id="contact"
        role="tabpanel"
        aria-labelledby="contact-tab"
      >
        <div class="d-flex flex-wrap justify-content-center mt-3 pt-3">
          <div v-if="contactSuccess.status" class="w-100">
            <div class="alert alert-dismissable alert-success text-center">
              {{ contactSuccess.msg }}
              <button
                type="button"
                class="close mb-3"
                data-dismiss="alert"
                aria-label="Close"
                @click="contactSuccess.status = false"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>

          <div v-if="contactError.status" class="w-100">
            <form-errors
              :errors="contactError.errors"
              @close="contactError.status = false"
            ></form-errors>
          </div>
        </div>

        <div
          class="d-flex flex-wrap justify-content-start"
          v-if="Object.keys(contacts).length > 0"
        >
        {{contacts}}
          <div
            class="col-12 col-lg-4 my-3"
            v-for="contact in contacts"
            :key="contact.id"
          >
            <contact-card
              :contact="contact"
              @updated="showContactSuccess"
              @error="showContactError"
              @closeAll="closeAll"
            ></contact-card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user_role"],
  data() {
    return {
      user: {
        customer: {},
        tickets: [],
        roles: [],
        department: {},
      },
      contacts: [],
      customerSuccess: {
        status: false,
        msg: "",
      },
      customerError: {
        status: false,
        errors: [],
      },
      contactSuccess: {
        status: false,
        msg: "",
      },
      contactError: {
        status: false,
        errors: [],
      },
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    };
  },
  beforeMount() {
    this.getUser();
  },
  methods: {
    closeAll() {
      this.customerError = {
        status: false,
        errors: [],
      };
      this.customerSuccess = {
        status: false,
        msg: "",
      };
      this.contactError = {
        status: false,
        errors: [],
      };
      this.contactSuccess = {
        status: false,
        msg: "",
      };
    },
    showContactSuccess(data) {
      console.log('data', data)
      this.contactSuccess = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.closeAll();
      }, 1500);
    },
    showContactError(data) {
      this.customerSuccess = {
        status: true,
        msg: data,
      };
    },
    showCustomerErrors(data) {
      this.customerError = {
        status: true,
        errors: data,
      };
    },
    showCustomerSuccess(data) {
      this.customerSuccess = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.closeAll();
      }, 1500);
    },
    handleCustomerSubmit() {
      this.closeAll();
      axios
        .put(`/api/customer/${this.user.customer.id}`, {
          cif: this.user.customer.cif,
          fiscal_name: this.user.customer.fiscal_name,
          comercial_name: this.user.customer.comercial_name,
          phone_1: this.user.customer.phone_1,
          phone_2: this.user.customer.phone_2,
          phone_3: this.user.customer.phone_3,
          email: this.user.customer.email,
          street: this.user.customer.street,
          city: this.user.customer.city,
          province: this.user.customer.province,
          country: this.user.customer.country,
          postcode: this.user.customer.postcode,
          shop: this.user.customer.shop,
          is_active: this.user.customer.is_active,
        })
        .then((res) => {
          if (res.data.success) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.showCustomerSuccess(res.data.msg);
          } else if (res.data.error) {
            this.showCustomerErrors(res.data.errors);
          }
        })
        .catch((err) => console.log(err));
    },
    getUser() {
      axios
        .get(`/api/user/${this.$route.params.user}`)
        .then((res) => {
          if (res.data.success) {
            this.user = res.data.user;
            this.contacts = res.data.contacts;
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>

<style>
</style>