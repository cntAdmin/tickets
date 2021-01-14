<template>
<div>
    <div class="row justify-content-center">
        <!-- TICKETS SEARCH FORM -->
        <div class="col-12 pb-3 border-bottom">
            <form>
                <div class="form-inline">
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
                        <label class="sr-only" for="dateFrom"># Ticket</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase"># Ticket</div>
                            </div>
                            <input type="text" v-model="id" @input="check_length('id')" class="form-control" title="Minimo 3 caracteres"
                                autofocus />
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
                        <label class="sr-only" for="dateFrom">Nombre</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Nombre</div>
                            </div>
                            <input type="text" v-model="user_name" @input="check_length('user')" class="form-control" title="Minimo 3 caracteres"/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
                        <label class="sr-only" for="dateFrom">Cod. Cliente</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Cod. Cliente</div>
                            </div>
                            <input type="text" v-model="customer_id" @input="check_length('customer')" class="form-control" title="Minimo 3 caracteres"/>
                        </div>
                    </div>
                </div>
                <div class="form-inline mt-2">
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
                        <label class="sr-only" for="dateFrom">Departamentos</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Departamentos</div>
                            </div>
                            <select v-model="department_id" @change="emit_to_parent" class="form-control">
                                <option value="">Todos</option>
                                <option v-for="dep in departments" :key="dep.id" :value="dep.id">
                                    {{ dep.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0">
                        <label class="sr-only" for="dateFrom">Estados</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Estados</div>
                            </div>
                            <select v-model="status" @change="emit_to_parent" class="form-control">
                                <option value="">Todos</option>
                                <option v-for="ts in ticket_statuses" :key="ts.id" :value="ts.id">
                                    {{ ts.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- TABLE OF TICKETS -->
        <div class="row justify-content-center mt-5" v-if="searching">
            <div class="sk-chase">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
            </div>
        </div>
        <div class="w-100" v-else>
            <div class="col-12 mt-3">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Asunto</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ticket in tickets.data" :key="ticket.id">
                            <th scope="row"><a :href="'ticket/'+ ticket.id">{{ ticket.id }}</a></th>
                            <td>{{ ticket.user.name }}</td>
                            <td>{{ ticket.customer.comercial_name }}</td>
                            <td>{{ ticket.department.name }}</td>
                            <td>{{ ticket.subject }}</td>
                            <td>
                                <button :class="'btn btn-sm btn-' + setColor(ticket.status.name) " type="button" :title="ticket.status.name" disabled>
                                    <i :class="'fa fa-' + setIcon(ticket.status.name) "></i>
                                </button>

                                <!-- SI ESTADO ES ABIERTO -->
                                <button v-if="ticket.status.id == 1 " class="btn btn-sm btn-primary" type="button" title="Cambiar estado">
                                    <i class="fa fa-exchange-alt"></i>
                                </button>
                                <button v-if="ticket.status.id == 1 " class="btn btn-sm btn-danger" type="button" title="Cambiar estado">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center mt-5">
                <pagination :data="tickets" @pagination-change-page="emit_to_parent"></pagination>
            </div>
        </div>
    </div>
</div>

</template>

<script>
export default {
    props: [
        'tickets',
        'searching'
    ],
    data() {
        return {
            id: '',
            departments: {},
            department_id: '',
            ticket_statuses: {},
            status: '',
            user_name:'',
            customer_id:'',
            page:1,
        }
    },
    mounted() {
        this.get_all_departments();
        this.get_all_ticket_statuses();
    },
    methods: {
        get_all_departments() {
            axios.get('get_all_departments')
                .then(res => {
                    this.departments = res.data;
                }).catch(ress => {
                    console.log(err)
                })
        },
        get_all_ticket_statuses() {
            axios.get('get_all_tickets')
                .then(res => {
                    this.ticket_statuses = res.data;
                }).catch(ress => {
                    console.log(err)
                });
        },
        check_length(to_check) {
            if(to_check === 'id' && (this.id.length === 0 || this.id.length > 2)){
                this.emit_to_parent();
            }

            if(to_check === 'user' && (this.user_name.length === 0 || this.user_name.length > 2)){
                this.emit_to_parent();
            }

            if(to_check === 'customer' && (this.customer.length === 0 || this.customer.length > 2)){
                this.emit_to_parent();
            }
        },
        emit_to_parent(page = 1){
            console.log('department',this.department_id);
            this.$emit('changed', {
                id : this.id,
                user : this.user_name,
                customer : this.customer_id,
                department : this.department_id,
                status : this.status,
                page : page
            });
        },
        setIcon(status_name) {
            switch (status_name) {
                case 'Abierto':
                    status_name = 'sticky-note';
                    break;
                case 'Cerrado':
                    status_name = 'check-circle';
                    break;
                case 'Resuelto':
                    status_name = 'check-double';
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
        }
    } // END METHODS
}
</script>

<style lang="scss">
.sk-chase {
    width: 40px;
    height: 40px;
    position: relative;
    animation: sk-chase 2.5s infinite linear both;
}

.sk-chase-dot {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    animation: sk-chase-dot 2.0s infinite ease-in-out both;
}

.sk-chase-dot:before {
    content: '';
    display: block;
    width: 25%;
    height: 25%;
    background-color: #212529;
    border-radius: 100%;
    animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
}

.sk-chase-dot:nth-child(1) {
    animation-delay: -1.1s;
}

.sk-chase-dot:nth-child(2) {
    animation-delay: -1.0s;
}

.sk-chase-dot:nth-child(3) {
    animation-delay: -0.9s;
}

.sk-chase-dot:nth-child(4) {
    animation-delay: -0.8s;
}

.sk-chase-dot:nth-child(5) {
    animation-delay: -0.7s;
}

.sk-chase-dot:nth-child(6) {
    animation-delay: -0.6s;
}

.sk-chase-dot:nth-child(1):before {
    animation-delay: -1.1s;
}

.sk-chase-dot:nth-child(2):before {
    animation-delay: -1.0s;
}

.sk-chase-dot:nth-child(3):before {
    animation-delay: -0.9s;
}

.sk-chase-dot:nth-child(4):before {
    animation-delay: -0.8s;
}

.sk-chase-dot:nth-child(5):before {
    animation-delay: -0.7s;
}

.sk-chase-dot:nth-child(6):before {
    animation-delay: -0.6s;
}

@keyframes sk-chase {
    100% {
        transform: rotate(360deg);
    }
}

@keyframes sk-chase-dot {

    80%,
    100% {
        transform: rotate(360deg);
    }
}

@keyframes sk-chase-dot-before {
    50% {
        transform: scale(0.4);
    }

    100%,
    0% {
        transform: scale(1.0);
    }
}
</style>