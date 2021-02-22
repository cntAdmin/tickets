<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-inline justify-content-center">
            <div class="form-group col-12 col-md-6">
              <label class="sr-only" for="name">nombre</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">nombre</div>
                </div>
                <input
                  ref="name"
                  v-model="search.name"
                  class="form-control"
                  type="text"
                  autofocus
                />
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
  },
  data() {
    return {
      search: {
        page: 1,
        name: "",
      },
    };
  },
  mounted() {
    this.$refs.name.focus();
  },
  methods: {
    handleSubmit() {
      this.search.page = 1;
      this.$emit("search", this.search);
    },
  },
};
</script>

<style>
</style>