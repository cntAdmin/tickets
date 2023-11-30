<template>
  <div class="d-flex justify-content-start flex-row flex-wrap">
    <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="!isFaq">
      <label class="sr-only" for="dateFrom">Cliente</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Cliente</div>
        </div>
        <input
          type="text"
          class="form-control"
          :value="
            ticket.customer
              ? ticket.customer.comercial_name
              : 'Sin cliente asignado'
          "
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="!isFaq">
      <label class="sr-only" for="dateFrom">Contacto</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Contacto</div>
        </div>
        <input
          type="text"
          class="form-control"
          :value="ticket.user ? ticket.user.name : 'Sin cliente asignado'"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Departamentos</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Departamentos</div>
        </div>
        <input
          type="text"
          class="form-control"
          :value="
            ticket.department ? ticket.department.name : 'Sin cliente asignado'
          "
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Nº Bastidor</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Nº Bastidor</div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="ticket.frame_id"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Matrícula</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Matrícula</div>
        </div>
        <input
          class="form-control"
          type="text"
          :value="ticket.plate"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Tipo de Motor</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Tipo de Motor</div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="ticket.engine_type"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Marca</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Marca</div>
        </div>
        <input
          class="form-control"
          type="text"
          :value="ticket.brand ? ticket.brand.name : 'Sin Asignar'"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Modelo</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Modelo</div>
        </div>
        <input
          class="form-control"
          type="text"
          :value="ticket.car_model ? ticket.car_model.name : 'Sin Asignar'"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Otra Marca/Modelo</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Otra Marca/Modelo</div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="ticket.other_brand_model"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mt-2">
      <label class="sr-only" for="dateFrom">Solicito</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Solicito</div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="ticket.ask_for"
          disabled
        />
      </div>
    </div>
    <div class="col-12 col-md-6 mt-2">
      <label class="sr-only" for="dateFrom">Asunto</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Asunto</div>
        </div>
        <input
          class="form-control"
          type="text"
          v-model="ticket.subject"
          disabled
        />
      </div>
    </div>
    <div class="form-group col-12 col-md-6 mt-2" v-if="!isFaq && user_role <= 4">
      <button
        type="button"
        :class="
          'btn btn-block text-white ' +
          (Object.keys(ticket.calls).length > 0 ? 'btn-danger' : 'btn-orange')
        "
        data-toggle="modal"
        data-target="#assignCall"
        @click="showCallsModal = true"
      >
        {{
          Object.keys(ticket.calls).length > 0
            ? "Llamadas seleccionada(s)"
            : "Asignar Llamadas"
        }}
      </button>

      <calls-modal
        :ticketID="ticket.id"
        v-show="showCallsModal"
        @close="showCallsModal = false"
      ></calls-modal>
    </div>

    <div class="form-group col-12 mt-2">
      <label class="sr-only" for="dateFrom">Descripcion</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Descripcion</div>
        </div>
      </div>
      <div class="border w-100 p-3" v-html="ticket.description"></div>
    </div>
    <div class="form-group col-12 mt-2">
      <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
      <div class="input-group w-100">
        <div class="input-group-prepend">
          <div class="input-group-text text-uppercase">Pruebas Realizadas</div>
        </div>
      </div>
      <div class="border w-100 p-3" v-html="ticket.tests_done"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ticket", "isFaq", "user_role"],
  data() {
    return {
      showCallsModal: false,
      calls: [],
    }
  }
};
</script>

<style>
</style>