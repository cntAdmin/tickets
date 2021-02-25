<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom"># Incidencia</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase"># Incidencia</div>
                </div>
                <input
                  type="text"
                  v-model="selected.ticket_id"
                  class="form-control"
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">Título</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Título</div>
                </div>
                <input
                  type="text"
                  v-model="selected.subject"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <label class="sr-only" for="dateFrom">ESTÁ PUBLICADO</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    ESTÁ PUBLICADO
                  </div>
                </div>
                <select class="form-control" v-model="selected.inFaqs">
                  <option value="" selected>-- TODOS --</option>
                  <option value="1">PUBLICADO</option>
                  <option value="2">NO PUBLICADO</option>
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
        page: 1,
        ticket_id: "",
        subject: "",
        inFaqs: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("search", this.selected);
    },
  },
};
</script>