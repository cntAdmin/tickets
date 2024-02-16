<template>
    <div>
        <div class="jumbotron mt-2">
            <!-- <div class="d-none d-xl-block">
                <h1 class="display-4">Hola, {{ user_name }}</h1>
            </div>
            <div class="d-xl-none d-block">
                <h1 class="display-5">Hola, {{ user_name }}</h1>
            </div> -->
            <div class="d-none d-xl-block">
                <h1 class="display-4">Hola</h1>
            </div>
            <hr class="my-4">
            <div class="justify-content-start">
                <p class="mb-0">Accesos de utilidad.</p>
                <a class="btn btn-secondary btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-ticket-alt"></i>
                    Nuevo Ticket
                </a>
                <a v-if="user_role == 1" class="btn btn-secondary btn-sm mr-1" href="/incidencias">
                    <i class="fas fa-fw fa-tools"></i>
                    Nuevo Parte de Trabajo
                </a>
                <a v-if="user_role == 1" class="btn btn-secondary btn-sm mr-1" href="/incidencias">
                    <i class="fas fa-fw fa-building"></i>
                    Lista Clientes
                </a>
                <a v-if="user_role == 1" class="btn btn-secondary btn-sm mr-1" href="/incidencias">
                    <i class="fas fa-fw fa-users"></i>
                    Lista Usuarios
                </a>
            </div>
            <div class="justify-content-start mt-3">
                <p class="mb-0">Visualizar tickets segun estado: </p>
                <a class="btn btn-secondary btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-list"></i>
                    Todos los tickets
                </a>
                <a class="btn btn-outline-dark btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-envelope"></i>
                    Nuevo(s)
                </a>
                <a class="btn btn-outline-info btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-envelope-open"></i>
                    Abierto(s)
                </a>
                <a class="btn btn-outline-success btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-check-square"></i>
                    Resuelto(s)
                </a>
                <a class="btn btn-outline-danger btn-sm mr-1" href="/incidencias" role="button">
                    <i class="fas fa-fw fa-window-close"></i>
                    Cancelado(s)
                </a>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-12 mt-2" v-if="user_role == 1">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">TOP TICKETS POR CLIENTES</p>
                    </div>
                    <div class="card-body">
                        <div v-if="loadingTopCustomerTickets">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="topCustomerTickets.length > 0">
                                <Bar :chart-options="chartOptions" :chart-data="barData"/>
                            </div>
                            <div v-else>
                                Sin tickets en la última semana.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2" v-if="user_role == 1">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">Estado clientes</p>
                    </div>
                    <div class="card-body">
                        <div v-if="loadingActiveCustomers">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="activeCustomers.length > 0">
                                <Doughnut :chart-options="chartOptions" :chart-data="doughnutData"/>
                            </div>
                            <div v-else>
                                No hay clientes en la base de datos
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">TOTAL TICKETS ÚLTIMA SEMANA (POR DÍAS)</p>
                    </div>
                    <div class="card-body">
                        <div v-if="loadingWeeklyTickets">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="weeklyTickets.length > 0">
                                <LineChartGenerator :chart-options="chartOptions" :chart-data="lineData"/>
                            </div>
                            <div v-else>
                                Sin tickets en la última semana.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6 col-xl-4 mt-2">
                <div class="card border border-dark">
                    <div class="card-header bg-dark">
                        <p class="text-white text-center mb-0">Estados de Tickets</p>
                    </div>
                    <div class="card-body">
                        <div v-if="loadingTicketStatuses">
                            <span>Cargando datos...</span>
                        </div>
                        <div v-else>
                            <div v-if="ticketStatuses.length > 0">
                                <Pie :chart-options="chartOptions" :chart-data="pieData"/>
                            </div>
                            <div v-else>
                                Sin tickets en la última semana.
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</template>

<script>

// import { Bar } from 'vue-chartjs/legacy'
// import { Doughnut } from 'vue-chartjs/legacy'
// import { Line as LineChartGenerator } from 'vue-chartjs/legacy'
// import { Pie } from 'vue-chartjs/legacy'
// import moment from 'moment'

// import {
//     Chart as ChartJS,
//     Title,
//     Tooltip,
//     Legend,
//     BarElement,
//     ArcElement,
//     LineElement,
//     LinearScale,
//     CategoryScale,
//     PointElement
// } from 'chart.js'

// ChartJS.register(Title, Tooltip, Legend, BarElement, ArcElement, LineElement, LinearScale, CategoryScale, PointElement)

export default {
    // name: 'Charts',
    // components: { 
    //     Bar,
    //     Doughnut,
    //     LineChartGenerator,
    //     Pie,
    // },
    // props: ['user_role', 'customer_id', 'user_id', 'user_name'],
    data() {
        return {
            // topCustomerTickets: [],
            // loadingTopCustomerTickets: true,
            // barEtiquetas: [],
            // barValores: [],
            // barData: {},

            // ticketStatuses: [],
            // loadingTicketStatuses: true,
            // pieEtiquetas: [],
            // pieValores: [],
            // pieData: {},

            // weeklyTickets: [],
            // loadingWeeklyTickets: true,
            // lineEtiquetas: [],
            // lineValores: [],
            // lineData: {},

            // activeCustomers: [],
            // loadingActiveCustomers: true,
            // doughnutData: {},

            // chartOptions: {
            //     responsive: true,
            //     maintainAspectRatio: false
            // }
        }
    },
    // filters:{
    //     currency(value) {
    //         return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(value);
    //     }
    // },
    // async mounted(){

        // if(this.user_role == 1 || this.user_role == 2)
        // {
        //     axios.get('/api/get_admin_charts').then((res) => {
        //         this.weeklyTickets = res.data.last_week_tickets;
        //         this.ticketStatuses = res.data.ticket_statuses;
        //         this.topCustomerTickets = res.data.top_customer_tickets;
        //         this.activeCustomers = res.data.customers_active;
        //         // console.log('activeCustomers');
        //         // console.log(this.activeCustomers);
        //     }).finally( () =>{

        //         // Top clientes con más tickets - BarChart
        //         this.loadingTopCustomerTickets = false;
        //         this.topCustomerTickets.forEach(customer_tickets => (
        //             this.barEtiquetas.push(customer_tickets.nombre),
        //             this.barValores.push(customer_tickets.total)
        //         ));
        //         this.barData = {
        //             labels: this.barEtiquetas,
        //             datasets: [{
        //                 label: 'TICKETS', 
        //                 backgroundColor:['#0dcaf0','#20c997','#198754','#ffc107','#fd7e14','#6610f2'],
        //                 data: this.barValores, 
        //             }]
        //         }

        //         // Tickets abiertos/cerrados - DoughnutChart
        //         this.loadingActiveCustomers = false;
        //         this.doughnutData = {
        //             labels: [
        //                 'Activos: ' + this.activeCustomers[1]['total'],
        //                 'Inactivos: ' + this.activeCustomers[0]['total'],
        //             ],
        //             datasets: [{
        //                 backgroundColor:['#38c172','#c51f1a'],
        //                 data: [this.activeCustomers[1]['total'], this.activeCustomers[0]['total']], 
        //             }]
        //         }

        //         // Tickets última semana - LineChart
        //         this.weeklyTickets.forEach(dayTicket => (
        //             this.lineEtiquetas.push(this.format_date(dayTicket.dia, 'dddd (MM-DD)')),
        //             this.lineValores.push(dayTicket.total)
        //         ));
        //         this.loadingWeeklyTickets = false;
        //         this.lineData = {
        //             labels: this.lineEtiquetas,
        //             datasets: [{
        //                 label: 'TICKETS POR DÍA',
        //                 backgroundColor: '#f87979',
        //                 data: this.lineValores
        //             }]
        //         }

        //         // Tickets por estado - PieChart
        //         this.ticketStatuses.forEach(topTicket => (
        //             this.pieEtiquetas.push(topTicket.estado),
        //             this.pieValores.push(topTicket.total)
        //         ));
        //         this.loadingTicketStatuses = false;
        //         this.pieData = {
        //             labels: this.pieEtiquetas,
        //             datasets: [{
        //                 backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
        //                 data: this.pieValores
        //             }]
        //         }
        //     });
        // }
    // },
    // methods: 
    // {
    //     format_date(value, format){
    //         return moment(String(value)).format(format)
    //     },
    // }
}
</script>
<style>

</style>