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
              class="close mb-3"
              @click="$emit('close')"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
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
                  class="form-control"
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
                  v-model="selected.customID"
                  class="form-control"
                  autofocus
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
                  v-model="selected.fiscalName"
                  class="form-control"
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
                  v-model="selected.comercialName"
                  class="form-control"
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
                  class="form-control"
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
                  v-model="selected.email"
                  class="form-control"
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
                  class="form-control"
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
                  class="form-control"
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
                  v-model="selected.city"
                  class="form-control"
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
                  class="form-control"
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
                  class="form-control"
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-block btn-secondary text-uppercase font-weight-bold">
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
  data() {
    return {
      selected: {
        customID: "",
        cif: "",
        fiscalName: "",
        comercialName: "",
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
    };
  },
  methods: {
      handleSubmit() {
        axios.post('/api/customer', {
            custom_id: this.selected.customID,
            cif: this.selected.cif,
            fiscal_name: this.selected.fiscalName,
            comercial_name: this.selected.comercialName,
            phone: this.selected.phone,
            email: this.selected.email,
            street: this.selected.street,
            city: this.selected.city,
            province: this.selected.province,
            country: this.selected.country,
            postcode: this.selected.postcode,
            shop: this.selected.shop,
            is_active: this.selected.isActive,
          }).then( res => {
              console.log(res.data)
              if(res.data.success) {
                  this.$emit('created', res.data.msg);
              }
          }).catch(err => {
              console.log(err)
          });
      },
  }
};
</script>