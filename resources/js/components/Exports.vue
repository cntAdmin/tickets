<template>
  <div class="d-flex my-3 justify-content-start">
    <div class="d-inline-flex w-100">
      <div class="col-6 col-xl-2">
        <button
          type="button"
          class="btn btn-sm btn-success btn-block font-weight-bold shadow"
          @click="$emit('exportFile', 'excel')"
        >
          Exportar Excel
        </button>
      </div>
      <div class="col-6 col-xl-2">
        <button
          type="button"
          class="btn btn-sm btn-warning text-dark btn-block font-weight-bold shadow"
          @click="$emit('exportFile', 'pdf')"
        >
          Exportar PDF
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>