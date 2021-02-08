<template>
    <div class="w-100">
        <div class="card shadow mt-3" v-if="tickets.total > 0">
            <div class="card-body">
                <table class="table table-hover table-striped shadow">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Asunto</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ticket in tickets.data" :key="ticket.id">
                            <th scope="row"><a :href="'ticket/'+ ticket.id">{{ ticket.custom_id }}</a></th>
                            <td>{{ ticket.user.name }}</td>
                            <td>{{ ticket.customer.comercial_name }}</td>
                            <td>{{ ticket.department.name }}</td>
                            <td>{{ ticket.subject }}</td>
                            <td>{{ ticket.created_at | moment("LTS") }}</td>
                            <td>
                                <a :href="'/ticket/' + ticket.id" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                <button :class="'btn btn-sm btn-' + setColor(ticket.status.name) " type="button"
                                    :title="ticket.status.name" disabled>
                                    <i :class="'fa fa-' + setIcon(ticket.status.name) "></i>
                                </button>

                                <!-- SI ESTADO ES ABIERTO -->
                                <button v-if="ticket.status.id == 1 " class="btn btn-sm btn-primary" type="button"
                                    title="Cambiar estado">
                                    <i class="fa fa-exchange-alt"></i>
                                </button>
                                <button v-if="ticket.deleted_at" class="btn btn-sm btn-danger" type="button" title="Cambiar estado"
                                    disabled>
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button v-else-if="ticket.status.id == 1 " class="btn btn-sm btn-danger" type="button"
                                    title="Cambiar estado">
                                    <i class="fa fa-trash"></i>
                                </button>
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
    </div>

</template>

<script>
export default {
  props: [
    'tickets', 'searched'
  ],
  methods: {
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