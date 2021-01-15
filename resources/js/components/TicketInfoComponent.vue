<template>
    <div class="card mt-4">
        <div class="card-body">
            <div class="form-inline">
                <div class="form-group col-12 col-md-6 col-lg-4">
                    <label class="sr-only" for="dateFrom">Cliente</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Cliente</div>
                        </div>
                        <select class="form-control" v-model="ticket.customer.id" disabled>
                            <option :value="ticket.customer.id">{{ ticket.customer.comercial_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4">
                    <label class="sr-only" for="dateFrom">Contacto</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Contacto</div>
                        </div>
                        <select v-show="ticket.user" class="form-control" v-model="ticket.user.id" disabled>
                            <option :value="ticket.user.id">{{ ticket.user.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4">
                    <label class="sr-only" for="dateFrom">Departamentos</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Departamentos</div>
                        </div>
                        <select v-show="ticket.department" class="form-control" v-model="ticket.department.name" disabled>
                            <option :value="ticket.department.id">
                                {{ ticket.department.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Nº Bastidor</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Nº Bastidor</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.frame_id" disabled />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Matrícula</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Matrícula</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.plate" disabled />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Tipo de Motor</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Tipo de Motor</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.engine_type" disabled />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Marca</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Marca</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.brand" disabled />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Modelo</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Modelo</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.model" disabled />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                    <label class="sr-only" for="dateFrom">Solicito</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Solicito</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.ask_for" disabled />
                    </div>
                </div>
            </div>
            <div class="form-inline mt-2">
                <div class="form-group col-12 col-md-6">
                    <label class="sr-only" for="dateFrom">Asunto</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Asunto</div>
                        </div>
                        <input class="form-control" type="text" v-model="ticket.subject" disabled />
                    </div>
                </div>
            </div>
            <div class="form-inline mt-2">
                <div class="form-group col-12">
                    <label class="sr-only" for="dateFrom">Descripcion</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Descripcion</div>
                        </div>
                    </div>
                    <div class="border w-100 p-3" v-html="ticket.description"></div>
                </div>
            </div>
            <div class="form-inline mt-2">
                <div class="form-group col-12">
                    <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Pruebas Realizadas</div>
                        </div>
                    </div>
                    <div class="border w-100 p-3" v-html="ticket.tests_done"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'ticket'
    ],
    data() {
        return {

        }
    },
    mounted() {

    },
    methods: {
    }
}
</script>