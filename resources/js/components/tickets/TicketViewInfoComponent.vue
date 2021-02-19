<template>
  <div class="card mt-4">
    <div class="card-header">
      <div class="d-flex justify-content-around">
        <div class="mr-auto">
          <h4 class="title text-uppercase" v-show="ticket">
            Ticket ({{ ticket.custom_id }})
          </h4>
        </div>

        <div class="ml-auto" v-show="ticket">
          <div class="form-inline">
            <div class="input-group">
              <span class="mr-3 mt-1">Añadir a FAQ's</span>
              <label class="switch">
                <input
                  type="checkbox"
                  v-model="ticket.knowledge_base"
                  @click="toggleFaqs"
                />
                <span class="slider round"></span>
              </label>
            </div>
            <router-link
              class="btn btn-sm btn-primary mx-3"
              :to="{ name: 'ticket.edit', params: { ticketID: ticketID } }"
            >
              Editar Ticket
            </router-link>
            <router-link
              class="btn btn-sm btn-secondary"
              :to="{ name: 'ticket.index' }"
            >
              Volver
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row justify-content-center">
        <div class="col-6 text-center">
          <div
            v-if="success.status === true"
            class="alert alert-success alert-dismissible fade show"
            role="alert"
          >
            {{ success.msg }}

            <button
              type="button"
              class="close"
              aria-label="Close"
              @click="success.status = false"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>

      <div class="form-inline">
        <div class="form-group col-12 col-md-6 col-lg-4">
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
        <div class="form-group col-12 col-md-6 col-lg-4">
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
        <div class="form-group col-12 col-md-6 col-lg-4">
          <label class="sr-only" for="dateFrom">Departamentos</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">Departamentos</div>
            </div>
            <input
              type="text"
              class="form-control"
              :value="
                ticket.department
                  ? ticket.department.name
                  : 'Sin cliente asignado'
              "
              disabled
            />
          </div>
        </div>
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
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
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
          <label class="sr-only" for="dateFrom">Matrícula</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">Matrícula</div>
            </div>
            <input
              class="form-control"
              type="text"
              v-model="ticket.plate"
              disabled
            />
          </div>
        </div>
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
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
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
          <label class="sr-only" for="dateFrom">Marca</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">Marca</div>
            </div>
            <input
              class="form-control"
              type="text"
              v-model="ticket.brand.name"
              disabled
            />
          </div>
        </div>
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
          <label class="sr-only" for="dateFrom">Modelo</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">Modelo</div>
            </div>
            <input
              class="form-control"
              type="text"
              v-model="ticket.car_model.name"
              disabled
            />
          </div>
        </div>
        <div
          class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2"
        >
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
      </div>
      <div class="form-inline mt-2">
        <div class="form-group col-12 col-md-6">
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
        <div class="form-group col-12 col-md-6">
          <button
            type="button"
            :class="'btn btn-block text-white ' + (Object.keys(ticket.calls).length > 0 ? 'btn-danger' : 'btn-info')"
            data-toggle="modal"
            data-target="#assignCall"
            @click="openCallsModal()">
            {{
              Object.keys(ticket.calls).length > 0
                ? "Llamadas seleccionada(s)"
                : "Asignar Llamadas"
            }}
          </button>

          <calls-modal
            :ticketID="ticket.id"
            :calls="calls"
            v-show="showCallsModal"
            @close="showCallsModal = false"
          ></calls-modal>
        </div>
      </div>

      <div class="form-inline mt-2">
        <div class="form-group col-12">
          <label class="sr-only" for="dateFrom">Descripcion</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">Descripcion</div>
            </div>
          </div>
          <div class="border w-100 p-3" v-html="ticket.description"></div>
        </div>
      </div>
      <div class="form-inline mt-2">
        <div class="form-group col-12">
          <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
          <div class="input-group w-100">
            <div class="input-group-prepend">
              <div class="input-group-text text-uppercase">
                Pruebas Realizadas
              </div>
            </div>
          </div>
          <div class="border w-100 p-3" v-html="ticket.tests_done"></div>
        </div>
      </div>
    </div>
    <ticket-view-calls
      v-show="Object.keys(ticket.calls).length > 0"
      :calls="ticket.calls"
    ></ticket-view-calls>
    <ticket-comments
      v-if="ticket.comments ? ticket.comments.length > 0 : 0"
      :comments="ticket.comments"
      :user="user"
      @succeeded="succeeded"
    >
    </ticket-comments>

    <ticket-new-coment
      :ticket_id="ticket.id"
      @load="get_ticket(ticket.id)"
    ></ticket-new-coment>
  </div>
</template>

<script>
export default {
  props: ["ticketID", "user_role", "user"],
  data() {
    return {
      ticket: {
        user: {},
        customer: {},
        brand: {},
        car_model: {},
        calls: [],
        comments:[],
        attachments: [],
      },
      calls: [],
      selected: {
        calls: [],
      },
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
      showCallsModal: false
    };
  },
  beforeMount() {
    this.get_ticket(this.ticketID);
    this.getCalls();
  },
  methods: {
    openCallsModal() {
      this.showCallsModal = true;
    },
    closeAll() {
      this.success.status = false;
      this.error.status = false;
    },
    toggleFaqs() {
      this.closeAll();
      axios.get(`/api/toogle_faqs_ticket/${this.ticket.id}`).then((res) => {
        if (res.data.success) {
          this.success = {
            status: true,
            msg: res.data.msg,
          };
        } else if (res.data.error) {
          this.error = {
            status: true,
            msg: res.data.msg,
          };
        }
      });
    },
    succeeded(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success.status = true;
      this.success.message = data;
    },
    get_ticket(ticket_id) {
      axios
        .get("/api/ticket/" + ticket_id)
        .then((res) => {
          this.ticket = res.data.ticket;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCalls() {
      axios
        .get("/api/get_all_calls")
        .then((res) => {
          this.calls = res.data.calls;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    selectedCalls(event) {
      this.selected.calls = event;
    },
    handleCalls() {
      axios
        .put("/api/ticket/" + parseInt(this.ticketID), {
          calls: this.selected.calls,
        })
        .then((res) => {
          if (res.data.success) {
            this.success.status = true;
            this.success.message = res.data.msg;
            setTimeout(() => {
              window.location.href = "/ticket/" + parseInt(this.ticketID);
            }, 1500);
          }
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
  },
};
</script>