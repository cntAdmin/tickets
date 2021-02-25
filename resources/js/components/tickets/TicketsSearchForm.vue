<template>
    <div class="w-100">
        <div class="card shadow">
            <div class="card-body">
                <form @submit.prevent="handleSubmit">
                    <div class="form-inline">
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom"># Incidencia</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase"># Incidencia</div>
                                </div>
                                <input type="text" v-model="selected.ticket_id" minlength="3" class="form-control"
                                    title="Minimo 3 caracteres" autofocus />
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom">Usuario</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Usuario</div>
                                </div>
                                <input type="text" v-model="selected.user_name" class="form-control" minlength="3"
                                    title="Minimo 3 caracteres" />
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom">Cod. Cliente</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Cod. Cliente</div>
                                </div>
                                <input type="text" v-model="selected.customer_custom_id" minlength="3"
                                    class="form-control" title="Minimo 3 caracteres" />
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                            <label class="sr-only" for="dateFrom">Nom. Cliente</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Nom. Cliente</div>
                                </div>
                                <input type="text" v-model="selected.customer_name" minlength="3"
                                    class="form-control" title="Minimo 3 caracteres" />
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                            <label class="sr-only" for="dateFrom">Desde</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Desde</div>
                                </div>
                                <input type="date" v-model="selected.date_from" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
                            <label class="sr-only" for="dateFrom">Hasta</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Hasta</div>
                                </div>
                                <input type="date" v-model="selected.date_to" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="form-inline mt-2">
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom">Departamentos</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Departamentos</div>
                                </div>
                                <select v-model="selected.department_id" class="form-control">
                                    <option value="">Todos</option>
                                    <option v-for="dep in departments" :key="dep.id" :value="dep.id">
                                        {{ dep.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom">Estados</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Estados</div>
                                </div>
                                <select v-model="selected.status" class="form-control">
                                    <option value="">Todos</option>
                                    <option v-for="ts in ticket_statuses" :key="ts.id" :value="ts.id">
                                        {{ ts.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-4">
                            <label class="sr-only" for="dateFrom">Publicado</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <div class="input-group-text text-uppercase">Publicado</div>
                                </div>
                                <select v-model="selected.knowledge_base" class="form-control">
                                    <option value="">-- Todos --</option>
                                    <option value="1">Publicado</option>
                                    <option value="2">No Publicado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button class="btn btn-sm btn-block btn-success text-uppercase">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    deactivated() {
        // GLOBAL FUNCTION IN APP.JS
        this.resetFields();
        this.handleSubmit();
    },
    props: ['ticket_statuses'],
    data() {
        return {
            departments: [],
            selected: {
                page: 1,
                ticket_id: '',
                user_name: '',
                customer_custom_id: '',
                customer_name: '',
                department_id: '',
                status: '',
                date_from: '',
                date_to: '',
                knowledge_base: ''
            },
        }
    },
    mounted() {
        this.get_all_departments();
    },
    methods: {
        handleSubmit() {
            this.selected.page = 1;
            this.$emit('search', this.selected);
        },
        get_all_departments() {
            axios.get('/api/get_all_departments')
                .then(res => {
                    this.departments = res.data.departments;
                }).catch(err => {
                    console.log(err)
                })
        },
    },
}
</script>

<style>

</style>