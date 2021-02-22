<template>
    <div class="card shadow">
        <div class="card-body">
            <form @submit="handleSubmit">
                <div class="row justify-content-start">
                    <div class="col-12 col-md-6 mt-2" v-show="user_role == 'superadmin' || user_role == 'admin' || user_role == 'department' || user_role == 'staff'">
                        <label class="sr-only">Cliente</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase py-1 ">Cliente</div>
                            </div>
                            <vue-select class="col-10 px-0" transition="vs__fade" :options="customers" label="comercial_name" itemid="id"
                                @input="setCustomer">
                                    <div slot="no-options">No hay opciones con esta busqueda</div>
                                    <template slot="option" slot-scope="option">
                                        {{ option.custom_id }} - {{ option.comercial_name }}
                                    </template>
                            </vue-select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">Desde</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Desde</div>
                            </div>
                        <input class="form-control" type="date" v-model="selected.date_from" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">hasta</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">hasta</div>
                            </div>
                        <input class="form-control" type="date" v-model="selected.date_to" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">Origen</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Origen</div>
                            </div>
                        <input class="form-control" type="text" v-model="selected.src" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">Destino</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Destino</div>
                            </div>
                            <input class="form-control" type="text" v-model="selected.dst" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">Estado</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Estado</div>
                            </div>
                            <select class="form-control" v-model="selected.status">
                                <option value="">Todos</option>
                                <option value="ANSWERED">Contestadas</option>
                                <option value="NO ANSWER">No Contestadas</option>
                                <option value="FAILED" v-if="!mobile">Fallidas</option>
                                <option value="BUSY" v-if="!mobile">Ocupado</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-2">
                        <label class="sr-only">Tipos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Tipos</div>
                            </div>
                            <select class="form-control" v-model="selected.type">
                                <option value="">Todas</option>
                                <option value="incoming">Entrantes</option>
                                <option value="outgoing">Salientes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-sm btn-success btn-block text-uppercase" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'customers', 'user_role', 'mobile'
    ],
    data() {
        return {
            selected: {
                date_from: this.$moment().subtract(30, 'days').format('YYYY-MM-DD'),
                date_to: this.$moment().format('YYYY-MM-DD'),
                customer_id: '',
                src: '',
                dst: '',
                dst_number: '',
                status: '',
                type: '',
            },
            dst_numbers: [],
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.selected.page = 1;
            this.$emit('submitted', this.selected)
        },
        setCustomer(value) {
            this.selected.customer_id = value ? value.id : null;
        },
    }
}
</script>