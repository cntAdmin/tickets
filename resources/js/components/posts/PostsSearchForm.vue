<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6">
              <label class="sr-only" for="dateFrom"
                >Buscar</label
              >
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Buscar
                  </div>
                </div>
                <input
                  class="form-control"
                  type="text"
                  v-model="selected.ticket_id"
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-3">
              <label class="sr-only" for="dateFrom">Desde</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Desde</div>
                </div>
                <input
                  class="form-control"
                  type="date"
                  v-model="selected.date_from"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-3">
              <label class="sr-only" for="dateFrom">Hasta</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Hasta</div>
                </div>
                <input
                  class="form-control"
                  type="date"
                  v-model="selected.date_to"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-3 mt-2">
              <label class="sr-only" for="dateFrom">Publicado</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Publicado</div>
                </div>
                <select v-model="selected.published" class="form-control">
                  <option value="">-- TODOS --</option>
                  <option value="1">Publicados</option>
                  <option value="2">No Publicados</option>
                </select>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-block btn-success text-uppercase">
              Buscar
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
    this.handleSubmit();
  },

  data() {
    return {
      selected: {
        text: "",
        date_from: "",
        date_to: "",
        published: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.selected.page = 1;
      this.$emit("search", this.selected);
    },
  },
};
</script>

<style>
</style>