<template>
    <div class="w-100">
        <div class="card shadow mt-3" v-if="tickets.total > 0">
            <div class="card-body">
                <table class="table table-hover table-striped shadow text-left">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <!-- <th scope="col">Usuario</th> -->
                            <th scope="col">Cliente</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Asunto</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ticket in tickets.data" :key="ticket.id">
                            <th scope="row">
                                <router-link :to="{name : 'ticket.show', params: {ticketID: ticket.id }}"  class="btn btn-sm btn-link text-uppercase">
                                    {{ ticket.custom_id }}
                                </router-link>
                            </th>
                            <!-- <td>{{ ticket.user.name }}</td> -->
                            <td>{{ ticket.customer.comercial_name }}</td>
                            <td>{{ ticket.department.name }}</td>
                            <td>{{ ticket.subject }}</td>
                            <td>{{ ticket.created_at | moment("LTS") }}</td>
                            <td>
                                <button :class="'mx-1 btn btn-sm btn-' + setColor(ticket.status.name) " type="button"
                                    :title="ticket.status.name" disabled>
                                    <i :class="'fa fa-' + setIcon(ticket.status.name) "></i>
                                </button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <router-link :to="{name : 'ticket.show', params: {ticketID: ticket.id }}"  class="btn btn-sm btn-success mx-1">
                                        <i class="fa fa-eye"></i>
                                    </router-link>
                                    <!-- SI ESTADO ES ABIERTO -->
                                    <div class="dropdown" v-if="ticket.status.id == 1 ">
                                        <button class="btn btn-sm btn-primary dropdown-toggle mx-1" type="button" id="statuses" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-exchange-alt"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="statuses">
                                            <button type="button" class="dropdown-item" @click="setStatus(ticket, 1)">Abierto</button>
                                            <button type="button" class="dropdown-item" @click="setStatus(ticket, 2)">Cerrado</button>
                                            <button type="button" class="dropdown-item" @click="setStatus(ticket, 3)">Resuelto</button>
                                        </div>
                                    </div>
                                    <button v-if="ticket.deleted_at" class="btn btn-sm btn-danger mx-1" type="button" title="Cambiar estado"
                                        disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button v-else-if="ticket.status.id == 1 " class="btn btn-sm btn-danger mx-1" type="button"
                                        title="Cambiar estado" @click="openDeleteModal(ticket)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :data="tickets" @pagination-change-page="emit_pagination" :limit="3" size="small"
                align="center">
                <span slot="prev-nav">&lt; Anterior</span>
                <span slot="next-nav">Siguiente &gt;</span>
            </pagination>
        </div>
        <div v-else class="mt-3 shadow">
            <div class="alert alert-warning text-center">
                Haga una nueva búsqueda
            </div>
        </div>
        <delete-modal v-show="showModal" :data="ticket" title="Ticket" @getDeleted="getDeleted" @close="showModal = false"></delete-modal>
    </div>

</template>

<script>
export default {
  props: [
    'tickets', 'searched'
  ],
  data() {
      return {
          showModal: false,
          ticket: {}
      }
  },
  methods: {
    getDeleted(data) {
        axios.delete('/api/ticket/' + this.ticket.id)
            .then( res => {
                if(res.data.success) {
                    this.$emit('close');
                    this.$emit('deleted', res.data.msg)
                }
                console.log(res.data)
            }).catch( err => {

            });
    },
    openDeleteModal(ticket) {
        this.showModal = true;
        this.ticket = ticket;
    },
    setStatus(ticket, id) {
          axios.get('/api/ticket/' + ticket.id + '/status/' + id)
            .then( res => {
                console.log(res.data)
            }).catch( err => {
                console.log(err)
            });
        this.emit_pagination(1);
    },
    setIcon(status_name) {
        switch (status_name) {
            case 'Abierto':
                status_name = 'envelope-open';
                break;
            case 'Cerrado':
                status_name = 'times-circle';
                break;
            case 'Resuelto':
                status_name = 'check-circle';
                break;
        
            default:
                status_name = 'clipboard-list';
                break;
        }
        return status_name;
    },
    setColor(status_name) {
        switch (status_name) {
            case 'Abierto':
                status_name = 'secondary';
                break;
            case 'Cerrado':
                status_name = 'info text-white';
                break;
            case 'Resuelto':
                status_name = 'success';
                break;
        
            default:
                status_name = 'dark';
                break;
        }

        return status_name;
    },
    emit_pagination(page) {
        this.searched.page = page;
        this.$emit('page', this.searched)
    }

  }
}
</script>