<template>
    <div>
        <div class="jumbotron mt-2">
            <h1 class="display-5">Hola Agente</h1>
            <hr class="my-4">
            <div class="justify-content-start">
                <router-link class="btn btn-secondary btn-sm mr-1" :to="{ name: 'ticket.index' }">
                    <span>Todas las Incidencias</span>
                </router-link>
                <router-link
                    v-for="status in ticket_statuses" :key="status.id"
                    :class=" status.id == 3 ? 'd-none' : 'btn btn-secondary btn-sm mr-1'"
                    :to="{ name: 'ticket.index', query: { status: status.id } }"
                >
                    <span class="ml-1">
                    {{ status.menu_name }}<span class="ml-2 badge badge-light" v-if="status.id == 1">{{ answered }}</span>
                    </span>
                </router-link>
            </div>
            <div class="my-5"></div>
            <h3>Seleccione período a mostrar</h3>
            <div class="justify-content-start">
                <router-link class="btn btn-orange btn-sm mr-2" :to="{ name: 'dashboard.index', query: { days: 7 } }">
                    7 Días
                </router-link>
                <router-link class="btn btn-orange btn-sm mr-2" :to="{ name: 'dashboard.index', query: { days: 15 } }">
                    15 Días
                </router-link>
                <router-link class="btn btn-orange btn-sm mr-2" :to="{ name: 'dashboard.index', query: { days: 30 } }">
                    1 Mes
                </router-link>
                <router-link class="btn btn-orange btn-sm mr-2" :to="{ name: 'dashboard.index', query: { days: 90 } }">
                    3 Mes
                </router-link>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <span class="h6">Período seleccionado: <strong class="font-weight-bold">{{  days }}</strong></span>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">PROMEDIO RESPUESTA DE AGENTES</p>
                    </div>
                    <div class="card-body">
                        <div v-if="barTiempoRespuestaAgentesDataloading">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="promedioTiempoRespuestaAgentes.length > 0">
                                <table class="table table-striped text-left">
                                    <thead>
                                        <tr>
                                            <th>Agente</th>
                                            <th>Promedio respuesta</th>
                                            <th>Incidencias resueltas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="promedio in promedioTiempoRespuestaAgentes" :key="promedio.id">
                                            <td>{{ promedio.name }}</td>
                                            <td>{{ toHoursAndMinutes(promedio.resolution_time / promedio.total_incidencias) }}</td>
                                            <td>{{ promedio.total_incidencias }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                Sin datos que cargar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">Nº INCIDENCIAS POR DÍAS</p>
                    </div>
                    <div class="card-body">
                        <div v-if="lineIncidenciasPorDiasDataloading">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="incidenciasPorDias.length > 0">
                                <LineChartGenerator v-if="loadedIncidenciasPorDias" :chart-options="chartOptions" :chart-data="lineIncidenciasPorDiasData"/>
                            </div>
                            <div v-else>
                                Sin datos que cargar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2"></div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">Nº INCIDENCIAS POR AGENTES</p>
                    </div>
                    <div class="card-body">
                        <div v-if="barIncidenciasPorAgentesDataloading">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="incidenciasPorAgente.length > 0">
                                <Bar v-if="loadedIncidenciasPorAgentes" :chart-options="chartOptions" :chart-data="barIncidenciasPorAgentesData"/>
                            </div>
                            <div v-else>
                                Sin datos que cargar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">TALLERES CON MÁS INCIDENCIAS</p>
                    </div>
                    <div class="card-body">
                        <Bar :chart-options="chartOptions" :chart-data="testData" hidden/>
                        <div v-if="barTopTalleresDataloading">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="incidenciasPorTalleres.length > 0">
                                <Bar v-if="loadedTopTalleres" :chart-options="chartOptions" :chart-data="barTopTalleresData"/>
                            </div>
                            <div v-else>
                                Sin datos que cargar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">ESTADO INCIDENCIAS </p>
                    </div>
                    <div class="card-body">
                        <div v-if="barIncidenciasEstadosDataloading">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="estadoIncidencias.length > 0">
                                <Bar 
                                    v-if="loadedIncidenciasEstados" 
                                    :chart-data="barIncidenciasEstadosData"
                                    :chart-options="chartOptions" 
                                />
                            </div>
                            <div v-else>
                                Sin datos que cargar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { Bar } from 'vue-chartjs/legacy'
import { Doughnut } from 'vue-chartjs/legacy'
import { Line as LineChartGenerator } from 'vue-chartjs/legacy'
import { Pie } from 'vue-chartjs/legacy'
import moment from 'moment'

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    ArcElement,
    LineElement,
    LinearScale,
    CategoryScale,
    PointElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, ArcElement, LineElement, LinearScale, CategoryScale, PointElement)

export default {
    props: ["user_role"],
    name: 'BarChart',
    components: {
        Bar,
        Doughnut,
        LineChartGenerator,
        Pie
    },
    data() {
        return {
            // datos de botones jumbotron
            ticket_statuses: [],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            answered: 0,
            test1: 30,
            days: this.$route.query.days,

            loadedIncidenciasPorDias: false,
            loadedTopTalleres: false,
            loadedIncidenciasPorAgentes: false,
            loadedIncidenciasEstados: false,

            // Incidencias por días - LineChart
            lineIncidenciasPorDiasDataloading: true,
            incidenciasPorDias: [],
            lineIncidenciasPorDiasEtiquetas: [],
            lineIncidenciasPorDiasValores: [],
            lineIncidenciasPorDiasData: {},

            // // Promedio tiempo de respuesta de los agentes BarChart
            barTiempoRespuestaAgentesDataloading: true,
            promedioTiempoRespuestaAgentes: [],

            // Top 5 Talleres con incidencias BarChart
            barTopTalleresDataloading: true,
            incidenciasPorTalleres: [],
            barTopTalleresEtiquetas: [],
            barTopTalleresValores: [],
            barTopTalleresData: {},

            // Agentes con incidecias - BarChart
            barIncidenciasPorAgentesDataloading: true,
            incidenciasPorAgente: [],
            barIncidenciasPorAgentesEtiquetas: [],
            barIncidenciasPorAgentesValores: [],
            barIncidenciasPorAgentesData: {},

            // Estado de Incidencias - BarChart
            barIncidenciasEstadosDataloading: true,
            estadoIncidencias: [],
            barIncidenciasEstadosEtiquetas: [],
            barIncidenciasEstadosValores: [],
            barIncidenciasEstadosData: {},

            testData: {},

            // Estos datos son común a todos los Charts
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    async mounted(){
        // Primera carga por defecto 30 días
        if(typeof this.days === 'undefined'){
            this.days = 30;
        }

        this.getTicketStatuses();
        this.getAnswered();

        axios.get('/api/get_admin_charts', {params: { days: this.days, }}).then( res => { 
            this.promedioTiempoRespuestaAgentes = res.data.promedioTiempoRespuestaAgentes;
            this.incidenciasPorDias = res.data.incidenciasPorDias;
            this.incidenciasPorAgente = res.data.incidenciasPorAgente;
            this.incidenciasPorTalleres = res.data.incidenciasPorTalleres;
            this.estadoIncidencias = res.data.estadoIncidencias;
        })
        .finally( () => {
            // Incidencias por día (última semana) - LineChart
            this.incidenciasPorDias.forEach(dia => (
                this.lineIncidenciasPorDiasEtiquetas.push(this.format_date(dia.dia, 'dddd (MM-DD)')),
                this.lineIncidenciasPorDiasValores.push(dia.total)
            ));
            this.lineIncidenciasPorDiasData = {
                labels: this.lineIncidenciasPorDiasEtiquetas,
                datasets: [{
                    label: 'TICKETS POR DÍA',
                    backgroundColor: '#f87979',
                    data: this.lineIncidenciasPorDiasValores
                }]
            }

            // Total incidencias por agente - BarChart
            this.incidenciasPorAgente.forEach(agente => (
                this.barIncidenciasPorAgentesEtiquetas.push(agente.name),
                this.barIncidenciasPorAgentesValores.push(agente.total)
            ));
            this.barIncidenciasPorAgentesData = {
                labels: this.barIncidenciasPorAgentesEtiquetas,
                datasets: [{
                    label: 'Nº INCIDENCIAS', 
                    backgroundColor:'#17A2B8',
                    data: this.barIncidenciasPorAgentesValores, 
                }]
            }

            // Top talleres con más incidencias - BarChart
            this.incidenciasPorTalleres.forEach(taller => (
                this.barTopTalleresEtiquetas.push(taller.name),
                this.barTopTalleresValores.push(taller.total)
            ));
            this.barTopTalleresData = {
                labels: this.barTopTalleresEtiquetas,
                datasets: [{
                    label: 'Nº INCIDENCIAS', 
                    backgroundColor:'#F68A59',
                    data: this.barTopTalleresValores, 
                }]
            }
            
            // Incidencias por estados - BarChart
            this.estadoIncidencias.forEach(estados => (
                this.barIncidenciasEstadosEtiquetas.push(estados.estado),
                this.barIncidenciasEstadosValores.push(estados.total)
            ));
            this.barIncidenciasEstadosData = {
                labels: this.barIncidenciasEstadosEtiquetas,
                datasets: [{
                    label: 'Nº INCIDENCIAS',
                    backgroundColor: '#6c757d',
                    data: this.barIncidenciasEstadosValores
                }]
            }

            // Quitar mensaje 'Cargando datos...' una vez se han recibido los datos
            this.barTiempoRespuestaAgentesDataloading = false;
            this.lineIncidenciasPorDiasDataloading = false;
            this.barIncidenciasPorAgentesDataloading = false;
            this.barTopTalleresDataloading = false;
            this.barIncidenciasEstadosDataloading = false;

            // Mostrar gráficos
            this.loadedIncidenciasPorDias = true;
            this.loadedTopTalleres = true;
            this.loadedIncidenciasPorAgentes = true;
            this.loadedIncidenciasEstados = true;
        });

        // Test
        this.testData = {
            labels: [
                'Test 1'
            ],
            datasets: [{
                label: 'Cabecera',
                backgroundColor: '#F68A59',
                data: [this.test1]
            }]
        }
    },
    methods: {
        getTicketStatuses() {
            axios.get("/api/get_all_ticket_statuses").then((res) => {
                this.ticket_statuses = res.data.ticket_statuses;
            });
        },
        getAnswered() {
            axios.get("/api/get_nuevos_tickets").then((res) => {
                this.answered = res.data.answered;
            });
        },
        format_date(value, format){
            return moment(String(value)).format(format)
        },
        toHoursAndMinutes(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60);
            const minutes = Math.round(totalMinutes % 60);
            return `${hours}h${minutes > 0 ? ` ${minutes}m` : ""}`;
        },
    },
}
</script>
<style>

</style>