<template>
    <div class="modal fade" id="assignCall">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">
                        <span><i class="fa fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Origen</th>
                                <th scope="col">Destino</th>
                                <th scope="col">Duración (s)</th>
                                <th scope="col">Selección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="call in calls" :key="call.id" class="text-center">
                                <th class="row">{{ call.id }}</th>
                                <td>{{ call.src }}</td>
                                <td>{{ call.dst }}</td>
                                <td>{{ call.billsec}}</td>
                                <td>
                                    <input type="checkbox" @click="selected_calls" :id="call.id" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" value="Guardar">
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: [
        'calls'
    ],
    data() {
        return {
            selected: {
                calls: [],
            }
        }
    },
    methods: {
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