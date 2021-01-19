<template>
    <div class="form-group col-12 col-md-6">
        <button type="button" :class="'btn btn-block text-white ' + (selected.calls.length >= 1 ? 'btn-danger' : 'btn-info')"
            data-toggle="modal" data-target="#assignCall" @click="get_calls()">{{ selected.calls.length >= 1 ? 'Llamadas seleccionada(s)' : 'Asignar Llamadas'}}</button>

    <div class="modal fade" id="assignCall">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span><i class="fa fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="searcher">
                        <form>
                            <div class="form-group col-12">
                                <label class="sr-only" for="dateFrom">Número Teléfono</label>
                                <div class="input-group w-100">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text text-uppercase">Número Teléfono</div>
                                    </div>
                                    <input class="form-control" type="text" v-model="selected.phone" @keyup="get_calls()"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-hover table-striped mt-3">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Origen</th>
                                <th scope="col">Destino</th>
                                <th scope="col">Duración (s)</th>
                                <th scope="col">Selección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="call in calls" :key="call.id" class="text-center">
                                <td>{{ call.src }}</td>
                                <td>{{ call.dst }}</td>
                                <td>{{ call.billsec}}</td>
                                <td>
                                    <input type="checkbox" @click="selected_calls" :id="call.id" 
                                        v-if="call.ticket_id === ticket_id" checked
                                    />
                                    <input type="checkbox" @click="selected_calls" :id="call.id" 
                                        v-else
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" value="Guardar" data-dismiss="modal" />
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    props: {
        ticket_id: {
            default: null,
            type: Number
        }
    },
    data() {
        return {
            selected: {
                calls: [],
            },
            calls:[]
        }
    },
    mounted() {
        // this.get_calls();
    },
    methods: {
        get_calls() {
            axios.get('/get_all_calls', { params: {
                    ticket_id: this.ticket_id,
                    phone_number: this.selected.phone
                }
            })
            .then(res => {
                this.calls = res.data.calls;
            }).catch(err => {
                console.log(err);
            })
        },

        selected_calls(e) {
            if(e.target.checked === false) {
                for( var i = 0; i < this.selected.calls.length; i++){ 
                    if ( this.selected.calls[i] === e.target.id) { 
                        this.selected.calls.splice(i, 1); 
                    }
                }
            } else {
                this.selected.calls.push(e.target.id);
            }

            return this.$emit('selectedCalls', this.selected.calls);
        }
    },
}
</script>