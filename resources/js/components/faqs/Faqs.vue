<template>
  <div class="mx-3">
    <div class="row">
      <card-counter
        title="Total"
        color="primary"
        :count="tickets.data ? tickets.total : 0"
        icon="clipboard-list"
        size="3"
      />
    </div>

    <tickets-search-form @search="getTickets" />

    <transition name="fade" v-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="tickets.data" mode="out-in">
      <tickets-table
        :tickets="tickets"
        @page="getTickets"
        :searched="searched"
      />
    </transition>
  </div>
</template>

<script>
export default {
    data() {
        return {
            tickets: [],
            searched: [],
            searching: false,
            success: {
                status: false,
                msg: ''
            }
        }
    },
    methods: {
        getTickets(data) {
            this.searching = true;
            this.searched = data ? data : this.searched;

            axios.get('/api/faqs', { params: {
                page: data ? data.page : null,
                ticket_id: data ? data.ticket_id : null,
                user_name: data ? data.user_name : null,
                customer_custom_id: data ? data.customer_custom_id : null,
                customer_name: data ? data.customer_name : null,
                department_id: data ? data.department_id : null,
                status: data ? data.status : this.$route.query.status,
                }
            }).then(res => {
                console.log(res.data)
                this.tickets = res.data.tickets;
                this.searching = false;
            }).catch(err => {
                console.log(err);
            })
        },
    }
};
</script>

<style>
</style>