<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="tickets.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped shadow text-left">
          <thead class="thead-dark">
            <tr>
              <th class="text-center" scope="col"># Incidencia</th>
              <th scope="col">Cliente</th>
              <th scope="col">Marca</th>
              <th scope="col">Asunto</th>
              <th class="text-center" scope="col">Fecha</th>
              <th class="text-center" scope="col">Estado</th>
              <th class="text-center" scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              :class="ticket.status.id == 1 ? 'bg-danger text-white' : ''
                      + user_role <= 4 && ticket.answered == false ? 'font-weight-bold' : ''
                      + user_role > 4 && ticket.answered == true ? 'font-weight-bold' : ''"
              v-for="ticket in tickets.data"
              :key="ticket.id"
            >
              <th class="text-center" scope="row">
                <router-link
                  :to="{ name: 'ticket.show', params: { ticketID: ticket.id } }"
                  :class="
                    ticket.status.id == 1
                      ? 'text-white'
                      : '' + ' btn btn-link btn-sm text-uppercase'
                  "
                >
                  {{ ticket.custom_id }}
                </router-link>
              </th>
              <td>{{ ticket.customer ? ticket.customer.comercial_name : '' }}</td>
              <td>{{ ticket.brand ? ticket.brand.name : ticket.other_brand_model }}</td>
              <td>{{ ticket.subject_short }}...</td>
              <td class="text-center">
                {{ ticket.updated_at | moment("DD-MM-YYYY HH:mm:ss") }}
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <div class="form-inline">
                    <button
                      :class="'btn btn-sm btn-' + setColor(ticket.status.id)"
                      type="button"
                      :title="ticket.status.name"
                      disabled
                    >
                      <i :class="'fa fa-' + setIcon(ticket.status.id)"></i>
                    </button>
                    <span class="btn btn-sm btn-link ml-2">
                      <i class="text-info fas fa-headset"></i
                      ><span class="badge badge-dark ml-2">{{
                        ticket.calls_count
                      }}</span>
                    </span>
                    <span class="btn btn-sm btn-link ml-2">
                      <i class="text-secondary fas fa-paperclip"></i
                      ><span class="badge badge-dark ml-2">
                        {{
                          Object.keys(ticket.comment_attachments).length +
                          Object.keys(ticket.attachments).length
                        }}
                      </span>
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <router-link
                    :to="{
                      name: 'ticket.show',
                      params: { ticketID: ticket.id },
                    }"
                    class="btn btn-sm btn-success mx-1"
                  >
                    <i class="fa fa-eye"></i>
                  </router-link>
                  <!-- SI ESTADO ES ABIERTO -->
                  <div class="dropdown">
                    <button
                      class="btn btn-sm btn-primary dropdown-toggle mx-1"
                      type="button"
                      id="statuses"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="fa fa-exchange-alt"></i>
                    </button>
                    <div
                      class="dropdown-menu dropdown-menu-right"
                      aria-labelledby="statuses"
                    >
                      <div v-for="status in ticket_statuses" :key="status.id">
                        <button
                          type="button"
                          class="dropdown-item"
                          @click.prevent="setStatus(ticket, status.id)"
                        >
                          {{ status.name }}
                        </button>
                      </div>
                      <button
                        v-if="ticket.status.id == 1"
                        type="button"
                        title="Cambiar estado"
                        class="dropdown-item"
                        @click="openDeleteModal(ticket)"
                      >
                        Borrar Ticket
                      </button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        :data="tickets"
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
      <div class="alert alert-warning text-center">Haga una nueva búsqueda</div>
    </div>
    <delete-modal
      v-show="showModal"
      :data="ticket"
      title="Ticket"
      @getDeleted="getDeleted"
      @close="showModal = false"
    ></delete-modal>
  </div>
</template>

<script>
export default {
  props: ["tickets", "searched", "ticket_statuses", "user_role"],
  data() {
    return {
      showModal: false,
      ticket: {},
    };
  },
  methods: {
    getDeleted() {
      axios
        .delete("/api/ticket/" + this.ticket.id)
        .then((res) => {
          if (res.data.success) {
            this.$emit("close");
            this.$emit("deleted", res.data.msg);
          }
        })
        .catch((err) => {});
    },
    openDeleteModal(ticket) {
      this.showModal = true;
      this.ticket = ticket;
    },
    setStatus(ticket, id) {
      axios
        .get("/api/ticket/" + ticket.id + "/status/" + id)
        .then((res) => {
          this.emit_pagination(1);
          this.$emit("getCounters");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    setIcon(status_id) {
      // console.log(status_id);
      switch (status_id) {
        case 2:
          return (status_id = "envelope-open");
          break;
        case 3:
          return (status_id = "times-circle");
          break;
        case 4:
          return (status_id = "check-circle");
          break;

        default:
          return (status_id = "clipboard-list");
          break;
      }
      return status_id;
    },
    setColor(status_id) {
      // console.log(status_id);
      switch (status_id) {
        case 2:
          return (status_id = "secondary");
          break;
        case 3:
          return (status_id = "info text-white");
          break;
        case 4:
          return (status_id = "success");
          break;

        default:
          return (status_id = "dark");
          break;
      }
    },
    emit_pagination(page) {
      this.searched.page = page;
      this.$emit("page", this.searched);
    },
  },
};
</script>