<template>
    <div class="w-100">
        <div class="card shadow mt-3" v-if="customers.total > 0">
            <div class="card-body">
                <table class="table table-hover table-striped shadow">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center" scope="col">#</th>
                            <th scope="col">CIF</th>
                            <th scope="col">Nombre Fiscal</th>
                            <th scope="col">Nombre Comercial</th>
                            <th scope="col">Email</th>
                            <th scope="col">Incidencias</th>
                            <th scope="col">Estado</th>
                            <th class="text-center" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cs in customers.data" :key="cs.id">
                            <th class="text-center" scope="row">{{ cs.custom_id }}</th>
                            <td>{{ cs.cif }}</td>
                            <td>{{ cs.fiscal_name }}</td>
                            <td>{{ cs.comercial_name }}</td>
                            <td>{{ cs.email }}</td>
                            <td>{{ cs.tickets_count }}</td>
                            <td>
                                <button v-if="cs.is_active" class="btn btn-sm btn-success" :title="cs.active_status" disabled>
                                    <i class="fa fa-check"></i>
                                </button>
                                <button v-else class="btn btn-sm btn-danger" :title="cs.active_status" disabled>
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <router-link class="btn btn-sm btn-success text-white mx-2" title="Crear un ticket"
                                        :to="{name: 'ticket.create', params: {customer_id: cs.id}}" >
                                        <i class="fa fa-plus-circle"></i>
                                    </router-link>
                                    <button class="btn btn-sm btn-info text-white mx-2" @click="$emit('edit', cs)" title="Editar Cliente">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger mx-2" @click="openDeleteModal(cs)" title="Eliminar Cliente">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <delete-modal v-show="deleteModal" @close="deleteModal = false" @getDeleted="getDeleted()" title="Cliente" :data="customer"></delete-modal>
            <pagination :data="customers" @pagination-change-page="emit_pagination" :limit="3" size="small"
                align="center">
                <span slot="prev-nav">&lt; Anterior</span>
                <span slot="next-nav">Siguiente &gt;</span>
            </pagination>
        </div>
        <div v-else class="mt-3 mx-3 shadow">
            <div class="alert alert-warning text-center">
                Haga una nueva búsqueda
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['customers', 'searched'],
    data() {
        return{
            customer: {},
            deleteModal: false
        }
    },
    methods: {
        emit_pagination(page){
            this.searched.page = page;
            this.$emit('page', this.searched)
        },
        openDeleteModal(customer) {
            this.deleteModal = true;
            this.customer = customer;
        },
        getDeleted(){
            axios.delete(`/api/customer/${this.customer.id}`)
                .then( res => {
                    if(res.data.success) {
                        this.$emit('deleted', res.data.msg)
                    }
                });
        }
    }
}
</script>

<style>

</style>