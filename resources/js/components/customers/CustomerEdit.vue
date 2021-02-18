<template>
  <div class="w-100 mt-3">
    <div class="card shadow border-info">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Editar Cliente</h3>
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
                  v-model="customer.cif"
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
                  v-model="customer.custom_id"
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
                  v-model="customer.fiscal_name"
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
                  v-model="customer.comercial_name"
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
                  v-model="customer.phone"
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
                  v-model="customer.email"
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
                  v-model="customer.street"
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
                  v-model="customer.city"
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
                  v-model="customer.province"
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
                  v-model="customer.country"
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
                  v-model="customer.shop"
                  class="form-control"
                />
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-block btn-info text-white text-uppercase font-weight-bold">
              Guardar Cliente
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props:['customer'],
  mounted() {console.log('customer', customer)},
  methods: {
      handleSubmit() {
        axios.put('/api/customer/'+ this.customer.id, {
            custom_id: this.customer.custom_id,
            cif: this.customer.cif,
            fiscal_name: this.customer.fiscal_name,
            comercial_name: this.customer.comercial_name,
            phone: this.customer.phone,
            email: this.customer.email,
            street: this.customer.street,
            city: this.customer.city,
            province: this.customer.province,
            country: this.customer.country,
            postcode: this.customer.postcode,
            shop: this.customer.shop,
            is_active: this.customer.is_active,
          }).then( res => {
              if(res.data.success) {
                  this.$emit('updated', res.data.msg);
              } else if(res.data.error) {
                  this.$emit('error', res.data.errors);
              }
          }).catch(err => {
              console.log(err)
          });
      },
  }
};
</script>