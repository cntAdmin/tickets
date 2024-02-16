<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="tickets.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped table-sm shadow text-left">
          <thead class="thead-dark">
            <tr>
              <th class="text-center"># Incidencia</th>
              <th class="text-left">Cliente</th>
              <th class="text-left" v-if="user_role != 7">Marca</th>
              <th class="text-left">Asunto</th>
              <th class="text-left">Fecha</th>
              <th class="text-left" v-if="user_role == 7">Respuesta</th>
              <th class="text-left">Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr :class="ticket.status.id == 1 ? 'bg-new-ticket text-white' : ticket.status.id == 6 ? 'bg-info text-dark' : ''
                      + user_role <= 4 && ticket.answered == false ? 'font-weight-bold' : ''
                      + user_role > 4 && ticket.answered == true ? 'font-weight-bold' : ''"
              v-for="ticket in tickets.data"
              :key="ticket.id"
            >
              <th class="text-center">
                <router-link :to="{ name: 'ticket.show', params: { ticketID: ticket.id } }"
                  :class="ticket.status.id == 1 ? 'text-white' : ticket.status.id == 6 ? 'text-dark' : '' + ' btn btn-link btn-sm text-uppercase'"
                >
                  {{ ticket.custom_id }}
                </router-link>
              </th>
              <td>{{ ticket.customer ? ticket.customer.comercial_name : '' }}</td>
              <td v-if="user_role != 7">{{ ticket.brand ? ticket.brand.name : ticket.other_brand_model }}</td>
              <td>{{ ticket.subject_short }}...</td>
              <td class="text-left">{{ ticket.updated_at | moment("DD/MM/YY - HH:mm") }}</td>
              <td class="text-left" v-if="user_role == 7">{{ getResolutionTime(ticket) }}</td>
              <td>
                <div class="d-flex justify-content-start">
                  <div class="form-inline">
                    <button
                      :class="'btn btn-sm btn-' + setColor(ticket.status.id)"
                      type="button"
                      :title="ticket.status.name"
                      disabled
                    >
                      <i :class="'fa fa-' + setIcon(ticket.status.id)"></i>
                    </button>
                    <span class="btn btn-sm btn-link ml-2" v-if="user_role != 7">
                      <i class="text-secondary fas fa-paperclip"></i
                      ><span class="badge badge-dark ml-2">
                        {{
                          Object.keys(ticket.comment_attachments).length +
                          Object.keys(ticket.attachments).length
                        }}
                      </span>
                    </span>
                    <span class="ml-2" v-if="ticket.assigned_to != null">
                      <button :title="ticket.assigned_to.name" type="button" disabled="disabled" class="text-uppercase badge badge-secondary">
                        {{ getAssignedToName(ticket.assigned_to.name) }}
                      </button>
                    </span>
                    <span v-else>
                      
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <router-link :to="{name: 'ticket.show',params: { ticketID: ticket.id },}" class="btn btn-sm btn-success mr-2">
                    <i class="fa fa-eye"></i>
                  </router-link>
                  <div class="btn-group dropleft" v-if="user_role != 7">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-bars"></i>
                    </button>
                    <div class="dropdown-menu">
                      <button
                        v-for="status in ticket_statuses" :key="status.id"
                        type="button"
                        class="dropdown-item" 
                        @click.prevent="setStatus(ticket, status.id)"
                      >
                        {{ status.name }}
                      </button>
                      <button
                        v-if="ticket.status.id == 1 || user_role == 1"
                        type="button" 
                        class="dropdown-item bg-danger text-white" 
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
import moment from "moment";

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
      axios.delete("/api/ticket/" + this.ticket.id).then((res) => {
        if (res.data.success) {
          this.$emit("close");
          this.$emit("deleted", res.data.msg);
        }
      }).catch((err) => {});
    },
    openDeleteModal(ticket) {
      this.showModal = true;
      this.ticket = ticket;
    },
    setStatus(ticket, id) {
      axios.get("/api/ticket/" + ticket.id + "/status/" + id).then((res) => {
        this.emit_pagination(1);
        this.$emit("getCounters");
      }).catch((err) => {
        console.log(err);
      });
    },
    setIcon(status_id) {
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
        case 5:
          return (status_id = "circle");
          break;
        case 6:
          return (status_id = "info-circle");
          break;
        default:
          return (status_id = "clipboard-list");
          break;
      }
      return status_id;
    },
    setColor(status_id) {
      switch (status_id) {
        case 2:
          return (status_id = "secondary");
          break;
        case 3:
          // return (status_id = "info text-white");
          return (status_id = "danger");
          break;
        case 4:
          return (status_id = "success");
          break;
        case 5:
          return (status_id = "dark");
          break;
        case 6:
          return (status_id = "light text-dark");
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
    getAssignedToName(text) {
      return text.substring(0, 2);
    },
    getResolutionTime(ticket) {

      if(ticket.ticket_status_id == 4){
        return this.toHoursAndMinutes(ticket.resolution_time);
      }
      else{
        return '';
      }
    },
    toHoursAndMinutes(totalMinutes) {
      const hours = Math.floor(totalMinutes / 60);
      const minutes = Math.round(totalMinutes % 60);
      return `${hours}h${minutes > 0 ? ` ${minutes}m` : ""}`;
    },
  },
};
</script>