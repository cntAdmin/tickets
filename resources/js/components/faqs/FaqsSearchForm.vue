<template>
  <form @submit.prevent="handleSubmit">
    <div class="d-flex justify-content-center flex-row flex-wrap mt-4">
      <div class="col-12 col-md-6 col-lg-4">
        <label class="sr-only" for="dateFrom">Buscar</label>
        <div class="input-group w-100">
          <div class="input-group-prepend">
            <div class="input-group-text text-uppercase">Buscar</div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="search.text"
            autofocus
          />
        </div>
      </div>
      <div class="col-12 col-md-2">
        <button
          class="btn btn-success btn-block text-uppercase"
          type="submit"
        >
          Buscar
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      search: {
        page: 1,
        text: "",
      },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("search", this.search);
    },
  },
};
</script>

<style>
</style>