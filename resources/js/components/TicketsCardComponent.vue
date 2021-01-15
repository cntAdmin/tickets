<template>
      <div class="card mt-4">
            <div class="card-header m-3 border-0">
                <div class="d-flex justify-content-around">
                    <div class="mr-auto">
                        <span class="text-uppercase">Tickets</span>
                    </div>
                    <div class="ml-aut">
                        <a href="ticket/create" class="btn btn-sm btn-primary text-uppercase">Nuevo Ticket <i class="fa fa-plus ml-3"></i></a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <h5 class="card-title">Búsqueda</h5>
                <tickets-search-and-table @changed="get_tickets($event)" :tickets="tickets" :searching="searching"></tickets-search-and-table>
          </div>
      </div>
</template>

<script>
export default {
    props: [
    ],
    data() {
        return {
            tickets: {},
            id: '',
            user: '',
            department: '',
            status: '',
            page: '',
            searching:'',
        }
    },
    mounted() {
        this.get_tickets({});
    },
    methods: {
        get_tickets({ id, user, customer, department, status, page = 1}) {
            this.searching = !this.searching;
            axios.get('/ticket?page=' + page, { params: {
                    id: id,
                    user: user,
                    customer: customer,
                    department: department,
                    status: status,
                }
            }).then(res => {
                setTimeout(() => {
                    this.tickets = res.data.tickets;
                    this.searching = !this.searching;
                }, 1000);
            }).catch(err => {
                console.log(err);
            })
        },
    }
}
</script>