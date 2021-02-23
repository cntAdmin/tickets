<template>
  <div class="form-inline my-3 justify-content-start">
    <button type="button" class="btn btn-sm btn-success" @click="$emit('exportFile', 'excel')">Exportar a Excel</button>
    <button type="button" class="btn btn-sm btn-warning text-dark ml-2" @click="$emit('exportFile', 'pdf')">Exportar a PDF</button>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>