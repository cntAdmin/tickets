<template>
  <div class="mx-3">
    <!-- Content Row -->
    <div class="row">
      <card-counter
        title="Totales"
        color="primary"
        :count="calls.data ? calls.total : 0"
        icon="headset"
        size="3"
      ></card-counter>
      <card-counter
        title="Entrantes"
        color="success"
        :count="incoming_counter"
        icon="phone-alt"
        size="3"
      ></card-counter>
      <card-counter
        title="Salientes"
        color="warning"
        :count="outgoing_counter"
        icon="phone"
        size="3"
      ></card-counter>
      <card-counter
        title="Minutos En Llamada"
        color="danger"
        :count="incall_time_counter"
        icon="hourglass-half"
        size="3"
      ></card-counter>
    </div>

    <!-- BUSCADOR DE LLAMADAS -->
    <calls-search-form
      :customers="customers"
      :user_role="user_role"
      @submitted="get_calls"
    ></calls-search-form>

    <!-- MOSTRAMOS SPINNER EN BUSQUEDAS 1s -->
    <transition name="fade" v-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>
    <!-- MOSTRAMOS LISTADO DE FACTURAS -->
    <div v-else-if="calls.data">
      <calls-table
        :user_role="user_role"
        :calls="calls"
        :type="call_type"
        @page="get_calls"
        :searched="searched"
      ></calls-table>
    </div>
    <!-- MOSTRAMOS ALERT CON INFO PARA HACER UNA BUSQUEDA LA PRIMERA VEZ QUE CARGA -->
    <div v-else class="mt-3">
      <div class="alert alert-success text-center shadow">
        Seleccione algunos parámetros y haga click en buscar
        <i class="far fa-smile-wink"></i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user_role"],
  mounted() {
    this.get_all_customers();
  },
  data() {
    return {
      customers: [],
      calls: {},
      call_type: "",
      total_counter: 0,
      incoming_counter: 0,
      outgoing_counter: 0,
      incall_time_counter: 0,
      searching: false,
      searched: {},
    };
  },
  methods: {
    get_all_customers() {
      axios
        .get("/api/get_all_customers")
        .then((res) => {
          this.customers = res.data.customers;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_calls(data) {
      this.searching = true;
      this.searched = data;

      this.get_counters();
      axios
        .get("/api/call", {
          params: {
            page: data ? data.page : null,
            dateFrom: data ? data.date_from : null,
            dateTo: data ? data.date_to : null,
            customer_id: data ? data.customer_id : null,
            src: data ? data.src : null,
            dst: data ? data.dst : null,
            dst_number: data ? data.dst_number : null,
            disposition: data ? data.status : null,
            type: data ? data.type : null,
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.call_type = data.type;
            this.calls = res.data.calls;
            this.searching = false;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_counters() {
      axios
        .get("/api/get_calls_count", {
          params: {
            dateFrom: this.searched.date_from,
            dateTo: this.searched.date_to,
            customer_id: this.searched.customer_id,
            src: this.searched.src,
            dst: this.searched.dst,
            dst_number: this.searched.dst_number,
            disposition: this.searched.status,
            type: this.searched.type,
          },
        })
        .then((res) => {
          this.total_counter = res.data.total_count;
          this.incoming_counter = res.data.incoming_count;
          this.outgoing_counter = res.data.outgoing_count;
          this.incall_time_counter =
            res.data.incall_time_count > 0
              ? parseFloat(this.$moment.duration(res.data.incall_time_count, "seconds").asMinutes()).toFixed()
                  : 0;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>