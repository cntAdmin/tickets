<template>
  <div class="flex-row justify-content-center mt-3">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="font-weight-bold text-uppercase">Registro de Llamadas</h4>
      </div>
      <div class="card-body py-2" v-for="call in calls" :key="call.id">
        <div class="d-flex flex-wrap justify-content-center">
          <div class="col-12">
            <label class="sr-only">Tipo</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Tipo</div>
              </div>
              <input
                class="form-control"
                type="text"
                :value="call.is_incoming ? 'Entrante' : 'Saliente'"
                disabled
              />
            </div>
          </div>
          <div class="col-12 mt-2">
            <label class="sr-only">Origen</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Origen</div>
              </div>
              <input
                class="form-control"
                type="text"
                :value="call.src"
                disabled
              />
            </div>
          </div>
          <div class="col-12 mt-2">
            <label class="sr-only">Destino</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Destino</div>
              </div>
              <input
                class="form-control"
                type="text"
                :value="call.dst"
                disabled
              />
            </div>
          </div>
          <div class="col-12 mt-2">
            <label class="sr-only">Fecha</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Fecha</div>
              </div>
              <input
                class="form-control"
                type="date"
                :value="call.start | moment('YYYY-MM-DD')"
                disabled
              />
            </div>
          </div>
          <div class="col-12 mt-2">
            <label class="sr-only">Duración <sub class="ml-1">(s)</sub></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Duración <sub class="ml-1">(s)</sub></div>
              </div>
              <input
                class="form-control"
                type="text"
                :value="call.duration"
                disabled
              />
            </div>
          </div>
        </div>
        <div class="dropdown-divider"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["calls"],
};
</script>

<style>
</style>