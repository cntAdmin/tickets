<template>
  <div class="flex-row justify-content-center">
    <div
      class="card mt-3 shadow"
      v-for="ticket in tickets.data"
      :key="ticket.id"
    >
      <div class="card-header">
        <h4 class="text-uppercase text-left font-weight-bold">
          <router-link
            :to="{
              name: 'ticket.show',
              params: { ticketID: ticket.id },
            }"
          >
            {{ ticket.custom_id }}
          </router-link>
        </h4>
        <span>{{ ticket.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</span>
      </div>
      <div class="card-body">
        <p class="text-truncate">
          {{ ticket.subject }}
        </p>
      </div>
      <div class="card-footer">
        <div class="d-flex flex-row">
          <div class="col-8">
            <div class="row justify-content-center">
              <span
                :class="
                  'disabled col-4 btn btn-sm btn-block btn-' +
                  setColor(ticket.status.name)
                "
                type="button"
                :title="ticket.status.name"
              >
                <i :class="'fas fa-' + setIcon(ticket.status.name)"></i>
              </span>
              <span class="col-4 btn btn-sm btn-link">
                <i class="text-info fas fa-headset"></i>
                <span class="badge badge-dark ml-2">
                  {{ ticket.calls_count }}
                </span>
              </span>
              <span class="col-4 btn btn-sm btn-link">
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
          <div class="col-4 px-2 py-0 m-0">
            <div class="row justify-content-between">
              <div class="col-6 px-2 py-0">
                <router-link
                  :to="{
                    name: 'ticket.show',
                    params: { ticketID: ticket.id },
                  }"
                  class="btn btn-sm btn-success btn-block"
                >
                  <i class="fa fa-eye"></i>
                </router-link>
              </div>
              <!-- SI ESTADO ES ABIERTO -->
              <div class="col-6 px-2 py-0">
                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-primary dropdown-toggle btn-block"
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["tickets", "ticket_statuses", "searched"],
  methods: {
    setIcon(status_name) {
      switch (status_name) {
        case "Abierto":
          status_name = "envelope-open";
          break;
        case "Cerrado":
          status_name = "times-circle";
          break;
        case "Resuelto":
          status_name = "check-circle";
          break;

        default:
          status_name = "clipboard-list";
          break;
      }
      return status_name;
    },
    setColor(status_name) {
      switch (status_name) {
        case "Abierto":
          status_name = "secondary";
          break;
        case "Cerrado":
          status_name = "info text-white";
          break;
        case "Resuelto":
          status_name = "success";
          break;

        default:
          status_name = "dark";
          break;
      }

      return status_name;
    },
  },
};
</script>

<style>
</style>