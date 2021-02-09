<template>
    <div class="w-100">
        <div class="card shadow mt-3" v-if="users.total > 0">
            <div class="card-body">
                <div v-if="is_success.status" :class="'alert alert-dismissible text-center fade show alert-' + is_success.color ">
                    {{ is_success.msg }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table class="table table-hover table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Email</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                            <th scope="row">{{ user.id }}</th>
                            <td>{{ user.name }}</td>
                            <td>{{ user.surname }}</td>
                            <td>{{ user.phone }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button v-if="department.id !== user.department_id" class="btn btn-sm btn-info text-white" :title="'Asignar a: ' + department.name " @click="assignUser(user)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button v-else class="btn btn-sm btn-danger" :title="'Desasignar de: ' + department.name " @click="unAssignUser(user)">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :data="users" @pagination-change-page="emit_pagination" :limit="3" size="small"
                align="center">
                <span slot="prev-nav">&lt; Anterior</span>
                <span slot="next-nav">Siguiente &gt;</span>
            </pagination>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'department', 'users', 'searched'
    ],
    data() {
        return {
            is_success: {
                status: false,
                msg: '',
                color: 'success'
            }
        }
    },
    methods: {
        emit_pagination(page){
            this.searched.page = page;
            this.$emit('page', this.searched)
        },
        assignUser(user) {
            axios.get('/assign_user/' + this.department.id + '/user/' + user.id)
                .then(res => {
                    if(res.data.success) {
                        this.is_success.status = true;
                        this.is_success.msg = res.data.msg;
                        this.is_success.color = res.data.color;
                        this.emit_pagination(this.searched.page);

                        setTimeout(() => {
                            this.is_success.status = false
                        }, 2000);
                    }
                }).catch( err => {
                    console.log(err)
                });
        },
        unAssignUser(user) {
            axios.get('/unassign_user/' + this.department.id + '/user/' + user.id)
                .then(res => {
                    if(res.data.success) {
                        this.is_success.status = true;
                        this.is_success.msg = res.data.msg;
                        this.is_success.color = res.data.color;
                        this.emit_pagination(this.searched.page);

                        setTimeout(() => {
                            this.is_success.status = false
                        }, 2000);
                    }
                }).catch( err => {
                    console.log(err)
                });
        }
    }
}
</script>