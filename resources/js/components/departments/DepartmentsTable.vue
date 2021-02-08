<template>
    <div class="w-100">
        <div class="card shadow mt-3" v-if="departments.total > 0">
            <div class="card-body">
                <table class="table table-hover table-striped shadow text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Código</th>
                            <th scope="col">Personas</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="department in departments.data" :key="department.id">
                            <th scope="row">{{ department.id }}</th>
                            <td>{{ department.name }}</td>
                            <td>{{ department.code }}</td>
                            <td>{{ department.users_count }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <div class="w-100">
                                        <button type="button" class="btn btn-sm btn-success mr-2">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info text-white mr-2"
                                            @click="$emit('edit', department)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger mr-2"
                                            @click="openDeleteModal(department)">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <delete-modal v-show="deleteModal" @close="deleteModal = false" @getDeleted="getDeleted()"
                title="departamento" :data="department" />
            <pagination :data="departments" @pagination-change-page="emit_pagination" :limit="3" size="small"
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
    props: [
        'departments', 'searched'
    ],
    data() {
        return {
            deleteModal: false,
            department: {},
        }
    },
    methods: {
        openDeleteModal(data) {
            this.deleteModal = true;
            this.department = data; 
        },
        getDeleted(data) {
            this.deleteModal = false
            axios.delete('/department/' + this.department.id)
                .then( res => {
                    if(res.data.success) {
                        this.$emit('success', res.data.msg)
                    } else if(res.data.error) {
                        this.$emit('error', res.data.msg)
                    }
                    console.log(res.data)
                }).catch( err => {
                    console.log(err);
                });
        },
        emit_pagination(page) {
            this.searched.page = page;
            this.$emit('page', this.searched)
        }
    }
}
</script>