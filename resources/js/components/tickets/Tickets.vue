<template>
    <div class="mx-3">
        <div class="row">
            <card-counter title="Total" color="primary" :count="total_count" icon="clipboard-list" size="3"/>
            <card-counter title="Abiertos" color="danger" :count="opened" icon="envelope-open" size="3" />
            <card-counter title="Cerrados" color="info" :count="closed" icon="times-circle" size="3" />
            <card-counter title="Resueltos" color="success" :count="resolved" icon="check-circle" size="3" />
        </div>

        <tickets-search-form @search="getTickets" />

        <transition name="fade" v-if="searching" mode="out-in">
            <div class="row justify-content-center mt-5">
                <spinner></spinner>
            </div>
        </transition>

        <transition name="fade" v-else-if="tickets.data" mode="out-in">
            <tickets-table :tickets="tickets" @page="getTickets" :searched="searched"/>
        </transition>
    </div>
</template>

<script>
export default {
    props: [
        'status'
    ],
    data() {
        return {
            tickets: [],
            total_count: 0,
            opened: 0,
            closed: 0,
            resolved: 0,
            searching: false,
            searched: []
        }
    },
    mounted() {
        this.getCount();
        if(this.status !== null) {
            this.getTickets();
        }
        console.log(this.status)
    },
    methods:{
        getCount() {
            axios.get('/get_ticket_counters/')
                .then(res => {
                    this.total_count = res.data.total_count;
                    this.opened = res.data.opened;
                    this.closed = res.data.closed;
                    this.resolved = res.data.resolved;
                }).catch(err => {
                    console.log(err);
                });
        },
        getTickets(data) {

            this.searching = true;
            this.searched = data;

            axios.get('/ticket', { params: {
                page: data ? data.page : null,
                ticket_id: data ? data.ticket_id : null,
                user: data ? data.user : null,
                customer: data ? data.customer : null,
                department: data ? data.department : null,
                status: data ? data.status : this.status,
                }
            }).then(res => {
                this.tickets = res.data.tickets;
                this.searching = false;
            }).catch(err => {
                console.log(err);
            })
        },
    }
}
</script>
<style lang="css">
  .fade-enter-active, .fade-edit-form-leave-active {
    transition: opacity .3s;
  }
  .fade-edit-form-enter, .fade-edit-form-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
</style>