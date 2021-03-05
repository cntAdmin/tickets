<template>
  <div class="mx-3 mt-3">
    <div class="row">
      <card-counter
        title="Nuevas"
        color="primary"
        :count="newTickets"
        icon="clipboard-list"
        size="3"
      />
      <card-counter
        title="Abiertas"
        color="danger"
        :count="opened"
        icon="envelope-open"
        size="3"
      />
      <card-counter
        title="Cerradas"
        color="info"
        :count="closed"
        icon="times-circle"
        size="3"
      />
      <card-counter
        title="Resueltas"
        color="success"
        :count="resolved"
        icon="check-circle"
        size="3"
      />
    </div>
    <div class="d-flex justify-content-center my-3">
      <router-link
        class="btn btn-secondary btn-sm text-uppercase btn-block"
        :to="{ name: 'ticket.create' }"
      >
        Crear Incidencia
      </router-link>
    </div>
    <div class="d-xl-none d-block d-flex justify-content-center my-3">
      <button
        class="btn btn-primary btn-sm btn-block text-uppercase font-weight-bold"
        type="button"
        data-toggle="collapse"
        data-target="#finder"
        aria-expanded="true"
        aria-controls="finder"
      >
        Buscador
      </button>
    </div>

    <tickets-search-form
      id="finder"
      class="collapse hidden d-xl-block"
      :ticket_statuses="ticket_statuses"
      @search="getTickets"
    />

    <div
      class="alert alert-dismissable alert-danger my-3"
      v-if="deleted.status"
    >
      {{ deleted.msg }}
    </div>
    <transition name="fade" v-else-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else-if="tickets.data" mode="out-in">
      <div v-if="tickets.total > 0">
        <div class="d-none d-xl-block">
          <exports @exportFile="exportFile"></exports>
        </div>
        <div class="d-none d-xl-block">
          <!-- DESCKTOP TABLE -->
          <tickets-table
            class="d-none d-xl-block"
            :tickets="tickets"
            :ticket_statuses="ticket_statuses"
            :searched="searched"
            @page="getTickets"
            @deleted="hasBeenDeleted"
            @getCounters="getCount()"
          />
        </div>
        <!-- MOBILE CARDS TABLE TABLE -->
        <div class="d-xl-none d-block">
          <mobile-tickets-cards-table
            :tickets="tickets"
            :ticket_statuses="ticket_statuses"
            :searched="searched"
            @page="getTickets"
            @deleted="hasBeenDeleted"
            @getCounters="getCount()"
          />
        </div>
      </div>
      <div v-else class="mt-3 shadow">
        <div class="alert alert-warning text-center">
          Haga una nueva búsqueda
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      tickets: [],
      ticket_statuses: [],
      total_count: 0,
      opened: 0,
      closed: 0,
      resolved: 0,
      newTickets: 0,
      searching: false,
      searched: {
        page: 1,
      },
      deleted: {
        status: false,
        msg: "",
      },
    };
  },
  activated() {
    this.searched.status = this.$route.query.status
      ? this.$route.query.status
      : null;
    this.getTickets();
    this.get_all_ticket_statuses();
  },
  deactivated() {
    this.closeAll();
  },
  methods: {
    exportFile(type) {
      axios
        .get("/api/export_tickets", {
          responseType: "arraybuffer",
          params: {
            page: this.searched.page ? this.searched.page : null,
            ticket_id: this.searched ? this.searched.ticket_id : null,
            user_name: this.searched ? this.searched.user_name : null,
            customer_custom_id: this.searched
              ? this.searched.customer_custom_id
              : null,
            customer_name: this.searched ? this.searched.customer_name : null,
            department_id: this.searched ? this.searched.department_id : null,
            status: this.searched
              ? this.searched.status
              : this.$route.query.status,
            date_from: this.searched ? this.searched.date_from : null,
            date_to: this.searched ? this.searched.date_to : null,
            knowledge_base: this.searched ? this.searched.knowledge_base : null,
            type: type,
          },
        })
        .then((res) => {
          // GET FILENAME FROM HEADERS
          var filename = "";
          var disposition = res.headers["content-disposition"];
          if (disposition && disposition.indexOf("attachment") !== -1) {
            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            var matches = filenameRegex.exec(disposition);
            if (matches != null && matches[1]) {
              filename = matches[1].replace(/['"]/g, "");
            }
          }
          // STORE FILE IN A BLOB
          let blob = new Blob([res.data], { type: "application/*" });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = filename;
          // DOWNLOAD IT
          link.click();
        })
        .catch((err) => console.log(err));
    },
    get_all_ticket_statuses() {
      axios
        .get("/api/get_all_ticket_statuses")
        .then((res) => {
          this.ticket_statuses = res.data.ticket_statuses;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    hasBeenDeleted(data) {
      this.deleted.status = true;
      this.deleted.msg = data;

      setTimeout(() => {
        this.getTickets();
      }, 1500);
    },
    getCount() {
      axios
        .get("/api/get_ticket_counters", {
          params: {
            ticket_id: this.searched ? this.searched.ticket_id : null,
            user_name: this.searched ? this.searched.user_name : null,
            customer_custom_id: this.searched
              ? this.searched.customer_custom_id
              : null,
            customer_name: this.searched ? this.searched.customer_name : null,
            department_id: this.searched ? this.searched.department_id : null,
            status: this.searched ? this.searched.status : null,
            date_from: this.searched ? this.searched.date_from : null,
            date_to: this.searched ? this.searched.date_to : null,
            knowledge_base: this.searched ? this.searched.knowledge_base : null,
          },
        })
        .then((res) => {
          this.total_count = res.data.total_count;
          this.opened = res.data.opened;
          this.closed = res.data.closed;
          this.resolved = res.data.resolved;
          this.newTickets = res.data.newTickets;
          this.$emit("getNewTickets");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getTickets(data) {
      document.querySelector("div#finder").classList.remove("show");
      this.closeAll();
      this.searching = true;
      this.searched = data ? data : this.searched;
      this.getCount();

      axios
        .get("/api/ticket", {
          params: {
            page: this.searched ? this.searched.page : null,
            ticket_id: this.searched ? this.searched.ticket_id : null,
            user_name: this.searched ? this.searched.user_name : null,
            customer_custom_id: this.searched
              ? this.searched.customer_custom_id
              : null,
            customer_name: this.searched ? this.searched.customer_name : null,
            department_id: this.searched ? this.searched.department_id : null,
            status: this.searched ? this.searched.status : null,
            date_from: this.searched ? this.searched.date_from : null,
            date_to: this.searched ? this.searched.date_to : null,
            knowledge_base: this.searched ? this.searched.knowledge_base : null,
          },
        })
        .then((res) => {
          this.tickets = res.data.tickets;
          this.searching = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    closeAll() {
      this.deleted.status = false;
    },
  },
};
</script>
<style lang="css">
.fade-enter-active,
.fade-edit-form-leave-active {
  transition: opacity 1s;
}
.fade-edit-form-enter, .fade-edit-form-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>