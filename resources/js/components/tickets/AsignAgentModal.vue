<template>

    <div class="modal fade" id="asignarAgente" tabindex="-1" role="dialog" aria-labelledby="asgignarAgenteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Ticket <span>({{ ticket.custom_id }})</span></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="margin-top: 0px; margin-bottom: 0px;">
                    <div class="form-group col-12 mt-2">
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Agente: </div>
                            </div>
                            <vue-select
                                class="col-8 px-0"
                                transition="vs__fade"
                                ref="agentSelect"
                                :options="agents"
                                label="name"
                                itemid="id"
                                @input="setAgent"
                            >
                            <div slot="no-options">No hay opciones con esta busqueda</div>
                                <template slot="option" slot-scope="option">
                                    {{ option.name }}
                                </template>
                            </vue-select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-secondary text-uppercase" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button class="btn btn-sm btn-success text-uppercase" @click="asignarAgente()">
                        Asignar Agente
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['ticket','agents'],
    data() {
        return {
            agent_id: 0,
        }
    },
    methods: {
        setAgent(value) {
            this.agent_id = value ? value.id : null;
        },
        asignarAgente() {
            axios.post("/api/asignar_agente", {
                agent_id: this.agent_id,
                ticket_id: this.ticket.id,
            }).then((res) => {
                // this.$emit("success", res.data.msg);
                window.location.reload();
            }).catch((err) => {
                console.log(err.response);
            });
        },
    },
};
</script>