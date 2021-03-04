<template>
  <div class="form-group col-12">
    <div class="modal fade" id="assignCall">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span><i class="fa fa-times"></i></span>
            </button>
          </div>

          <div class="modal-body">
            <div id="searcher">
              <form @submit.prevent="get_calls()">
                <div class="form-group col-12">
                  <label class="sr-only" for="dateFrom">Número Teléfono</label>
                  <div class="input-group w-100">
                    <div class="input-group-prepend">
                      <div class="input-group-text text-uppercase">
                        Número Teléfono
                      </div>
                    </div>
                    <input
                      class="form-control"
                      type="text"
                      v-model="selected.phone"
                      @keyup="get_calls()"
                    />
                  </div>
                </div>
              </form>
            </div>
            <div v-if="selected.phone !== ''">
              <div
                v-show="success.status"
                class="alert alert-success alert-dismissible fade show text-center my-3"
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
              <table class="table table-hover table-striped mt-3">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">Origen</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Duración (s)</th>
                    <th class="text-center" scope="col">Fecha y Hora</th>
                    <th class="text-center" scope="col">
                      Asignar / Desasignar
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(call, idx) in calls.data"
                    :key="idx"
                    class="text-center"
                  >
                    <td>{{ call.src }}</td>
                    <td>{{ call.dst }}</td>
                    <td>{{ call.duration }}</td>
                    <td>{{ call.start | moment('DD-MM-YYYY HH:mm:ss') }}</td>
                    <td>
                      <div class="d-flex justify-content-center">
                        <label class="switch">
                          <input
                            v-if="call.ticket_id === ticketID"
                            type="checkbox"
                            @click="toggleCallTicket($event, call)"
                            checked
                          />
                          <input
                            v-else
                            type="checkbox"
                            @click="toggleCallTicket($event, call)"
                          />
                          <span class="slider round"></span>
                        </label>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <pagination
                :data="calls"
                @pagination-change-page="emit_pagination"
                :limit="3"
                size="small"
                align="center"
              >
                <span slot="prev-nav">&lt; Anterior</span>
                <span slot="next-nav">Siguiente &gt;</span>
              </pagination>
            </div>
            <div v-else class="mt-3 shadow">
              <div class="alert alert-warning text-center">
                Haga una nueva búsqueda
              </div>
            </div>
            <div class="modal-footer">
              <input
                type="button"
                class="btn btn-secondary"
                value="Salir"
                data-dismiss="modal"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  deactivated() {
    this.resetFields();
  },
  props: ["ticketID"],
  data() {
    return {
      calls: [],
      success: {
        status: false,
        msg: "",
      },
      selected: {
        page: 1,
        phone: "",
      },
    };
  },
  mounted() {
    this.get_calls();
  },
  methods: {
    emit_pagination(page) {
      this.selected.page = page;
      this.get_calls();
    },
    toggleCallTicket(e, call) {
        this.success.status = false;
      axios
        .put(`/api/call/${call.id}/ticket/${this.ticketID}`, {
          toggle: e.target.checked,
        })
        .then((res) => {
          if (res.data.success) {
            this.success = {
              status: true,
              msg: res.data.msg,
            };
          }
        });
    },
    get_calls() {
      axios
        .get("/api/asignable_calls", {
          params: {
            page: this.selected.page,
            phone_number: this.selected.phone,
            type: 'finder'
          },
        })
        .then((res) => {
          this.calls = res.data.calls;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>