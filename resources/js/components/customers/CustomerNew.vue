<template>
  <div class="w-100 mt-3">
    <div class="card shadow border-secondary">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Nuevo Cliente</h3>
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
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">CIF</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">CIF</div>
                </div>
                <input
                  type="text"
                  v-model="selected.cif"
                  :class="
                    [error.errors.cif ? 'is-invalid' : ''] + ' form-control'
                  "
                  autofocus
                />
              </div>
            </div>
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
                  v-model="selected.custom_id"
                  :class="
                    [error.errors.custom_id ? 'is-invalid' : ''] + ' form-control'
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
                  v-model="selected.fiscal_name"
                  :class="
                    [error.errors.fiscal_name ? 'is-invalid' : ''] + ' form-control'
                  "
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
                  v-model="selected.comercial_name"
                  :class="
                    [error.errors.comercial_name ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Teléfono 1</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Teléfono 1</div>
                </div>
                <input
                  type="text"
                  v-model="selected.phone_1"
                  :class="
                    [error.errors.phone_1 ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Teléfono 2</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Teléfono 2</div>
                </div>
                <input
                  type="text"
                  v-model="selected.phone_2"
                  :class="
                    [error.errors.phone_2 ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Teléfono 3</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Teléfono 3</div>
                </div>
                <input
                  type="text"
                  v-model="selected.phone_3"
                  :class="
                    [error.errors.phone_3 ? 'is-invalid' : ''] + ' form-control'
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
              <label class="sr-only" for="dateFrom">Dirección</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Dirección</div>
                </div>
                <input
                  type="text"
                  v-model="selected.street"
                  :class="
                    [error.errors.street ? 'is-invalid' : ''] + ' form-control'
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
                  v-model="selected.city"
                  :class="
                    [error.errors.city ? 'is-invalid' : ''] + ' form-control'
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
                  v-model="selected.province"
                  :class="
                    [error.errors.province ? 'is-invalid' : ''] + ' form-control'
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
                  v-model="selected.country"
                  :class="
                    [error.errors.country ? 'is-invalid' : ''] + ' form-control'
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
                  v-model="selected.shop"
                  :class="
                    [error.errors.shop ? 'is-invalid' : ''] + ' form-control'
                  "
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button
              type="submit"
              class="btn btn-sm btn-block btn-secondary text-uppercase font-weight-bold"
            >
              Nuevo Cliente
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
  },

  data() {
    return {
      selected: {
        custom_id: "",
        cif: "",
        fiscal_name: "",
        comercial_name: "",
        phone: "",
        email: "",
        street: "",
        city: "",
        province: "",
        country: "",
        postcode: "",
        shop: "",
        isActive: "",
      },
      error: {
        errors: []
      }
    };
  },
  methods: {
    handleSubmit() {
      axios
        .post("/api/customer", {
          custom_id: this.selected.custom_id,
          cif: this.selected.cif,
          fiscal_name: this.selected.fiscal_name,
          comercial_name: this.selected.comercial_name,
          phone_1: this.selected.phone_1,
          phone_2: this.selected.phone_2,
          phone_3: this.selected.phone_3,
          email: this.selected.email,
          street: this.selected.street,
          city: this.selected.city,
          province: this.selected.province,
          country: this.selected.country,
          postcode: this.selected.postcode,
          shop: this.selected.shop,
          is_active: this.selected.isActive,
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
  },
};
</script>