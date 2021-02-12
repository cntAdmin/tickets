<template>
    <div class="mx-3">
        <div class="row">
            <card-counter title="Total" color="primary" :count="total_count" icon="clipboard-list" size="3"/>
            <card-counter title="Abiertos" color="danger" :count="opened" icon="envelope-open" size="3" />
            <card-counter title="Cerrados" color="info" :count="closed" icon="times-circle" size="3" />
            <card-counter title="Resueltos" color="success" :count="resolved" icon="check-circle" size="3" />
        </div>
        <div class="d-flex justify-content-center my-3">
            <router-link class="btn btn-secondary btn-sm text-uppercase btn-block" :to="{name: 'ticket.create'}">
                Crear Ticket
            </router-link>
        </div>
        <tickets-search-form @search="getTickets" />

        <div class="alert alert-dismissable alert-danger my-3" v-if="deleted.status">
            {{ deleted.msg }}
        </div>
        <transition name="fade" v-else-if="searching" mode="out-in">
            <div class="row justify-content-center mt-5">
                <spinner></spinner>
            </div>
        </transition>

        <transition name="fade" v-else-if="tickets.data" mode="out-in">
            <tickets-table :tickets="tickets" @page="getTickets" :searched="searched" @deleted="hasBeenDeleted"/>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            tickets: [],
            total_count: 0,
            opened: 0,
            closed: 0,
            resolved: 0,
            searching: false,
            searched: {
                page:1
            },
            deleted: {
                status: false,
                msg: ''
            }
        }
    },
    mounted() {
        this.getCount();
        this.getTickets();
    },
    methods:{
        hasBeenDeleted(data) {
            this.deleted.status = true;
            this.deleted.msg = data;

            setTimeout(() => {
                this.getTickets()
            }, 1500);

        },
        getCount() {
            axios.get('/api/get_ticket_counters/')
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
            this.closeAll();
            this.searching = true;
            this.searched = data ? data : this.searched;

            axios.get('/api/ticket', { params: {
                page: data ? data.page : null,
                ticket_id: data ? data.ticket_id : null,
                user: data ? data.user : null,
                customer: data ? data.customer : null,
                department: data ? data.department : null,
                status: data ? data.status : this.$route.query.status,
                }
            }).then(res => {
                this.tickets = res.data.tickets;
                this.searching = false;
            }).catch(err => {
                console.log(err);
            })
        },
        closeAll() {
            this.deleted.status = false;
        }
    }
}
</script>
<style lang="css">
  .fade-enter-active, .fade-edit-form-leave-active {
    transition: opacity 1s;
  }
  .fade-edit-form-enter, .fade-edit-form-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
</style>