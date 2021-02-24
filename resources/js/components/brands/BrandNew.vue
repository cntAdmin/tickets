<template>
  <div class="row my-3">
    <div class="card shadow w-100 border-secondary">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <div class="mr-auto">
            <h3 class="text-uppercase">Nueva Marca</h3>
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
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
              <label class="sr-only" for="dateFrom">Nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nombre</div>
                </div>
                <input
                  ref="name"
                  type="text"
                  v-model="selected.name"
                  :class="
                    [error.errors.name ? 'is-invalid' : ''] + ' form-control'
                  "
                  required
                  autofocus
                />
              </div>
            </div>
            <button class="btn btn-sm btn-secondary text-uppercase ml-3">
              <i class="fa fa-plus"></i><span class="ml-3">Crear Marca</span>
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
    this.resetFields();
  },
  data() {
    return {
      selected: {
        name: ''
      },
      error: {
        errors: []
      }
    }
  },
  mounted() {
    this.$refs.name.focus();
  },
  methods: {
    handleSubmit() {
      if(!this.selected.name) return;

      axios.post('/api/brand', {
        name: this.selected.name
      }).then((res) => {
        console.log(res.data)
          if (res.data.success) {
            this.$emit("success", res.data.msg);
          } else if (res.data.error) {
            this.error.errors = res.data.errors;
            this.$emit("error", res.data.errors);
          }
        }).catch((err) => console.log(err));
    }
  }
}
</script>

<style>
</style>