<template>
  <div class="flex-row justify-content-center" id="cards-list">
    <div class="card mt-3 shadow" v-for="ticket in tickets" :key="ticket.id">
      <div class="card-header">
        <h4 class="text-uppercase text-left font-weight-bold">
          <router-link
            :to="{
              name: 'ticket.show',
              params: { ticketID: ticket.id },
            }"
          >
            {{ ticket.custom_id }}
          </router-link>
        </h4>
        <span>{{ ticket.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</span>
      </div>
      <div class="card-body">
        <p class="text-truncate">
          {{ ticket.subject }}
        </p>
      </div>
      <div class="card-footer">
        <div class="d-flex flex-row">
          <div class="col-8">
            <div class="row justify-content-center">
              <span
                :class="
                  'disabled col-4 btn btn-sm btn-block btn-' +
                  setColor(ticket.status.id)
                "
                type="button"
                :title="ticket.status.id"
              >
                <i :class="'fas fa-' + setIcon(ticket.status.id)"></i>
              </span>
              <span class="col-4 btn btn-sm btn-link">
                <i class="text-info fas fa-headset"></i>
                <span class="badge badge-dark ml-2">
                  {{ ticket.calls_count }}
                </span>
              </span>
              <span class="col-4 btn btn-sm btn-link">
                <i class="text-secondary fas fa-paperclip"></i
                ><span class="badge badge-dark ml-2">
                  {{
                    Object.keys(ticket.comment_attachments).length +
                    Object.keys(ticket.attachments).length
                  }}
                </span>
              </span>
            </div>
          </div>
          <div class="col-4 px-2 py-0 m-0">
            <div class="row justify-content-between">
              <div class="col-6 px-2 py-0">
                <router-link
                  :to="{
                    name: 'ticket.show',
                    params: { ticketID: ticket.id },
                  }"
                  class="btn btn-sm btn-success btn-block"
                >
                  <i class="fa fa-eye"></i>
                </router-link>
              </div>
              <!-- SI ESTADO ES ABIERTO -->
              <div class="col-6 px-2 py-0">
                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-primary dropdown-toggle btn-block"
                    type="button"
                    id="statuses"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-exchange-alt"></i>
                  </button>
                  <div
                    class="dropdown-menu dropdown-menu-right"
                    aria-labelledby="statuses"
                  >
                    <div v-for="status in ticket_statuses" :key="status.id">
                      <button
                        type="button"
                        class="dropdown-item"
                        @click.prevent="setStatus(ticket, status.id)"
                      >
                        {{ status.name }}
                      </button>
                    </div>
                    <button
                      v-if="ticket.status.id == 1"
                      type="button"
                      title="Cambiar estado"
                      class="dropdown-item"
                      @click="openDeleteModal(ticket)"
                    >
                      Borrar Ticket
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>  <!-- END OF CARD FOOTER -->
    </div>  <!-- END OF CARD -->
    <transition name="fade" v-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ["ticket_statuses", "searched"],
  data() {
    return {
      tickets: [],
      searching: false,
      offset: 0,
      dontLoad: false
    };
  },
  mounted() {
    window.onscroll = () => {
      this.scroll();
    };
    this.loadMore();
  },
  methods: {
    scroll() {
      let bottomOfWindow =
        Number(
          (
            Math.max(
              window.pageYOffset,
              document.documentElement.scrollTop,
              document.body.scrollTop
            ) + window.innerHeight
          ).toFixed(0)
        ) === document.documentElement.offsetHeight;

      if (bottomOfWindow) {
        this.loadMore(); // replace it with your code
      }
    },
    loadMore() {
      if(!this.dontLoad && !this.searching) {
        this.searching = true;

        axios
          .get("/api/mobile_ticket", {
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
              offset: this.offset,
            },
          })
          .then((res) => {
            if(res.data.tickets.length === 0) {
                return this.dontLoad = true;
            } else if(res.data.success) {
              this.offset += 10;
              setTimeout(() => {
                this.tickets.push(...res.data.tickets);
              }, 1000);
            }
          })
          .catch((err) => console.log(err));
          this.searching = false;
      }
    },
    setIcon(status_id) {
      switch (status_id) {
        case 2:
          return (status_id = "envelope-open");
          break;
        case 3:
          return (status_id = "times-circle");
          break;
        case 4:
          return (status_id = "check-circle");
          break;

        default:
          return (status_id = "clipboard-list");
          break;
      }
    },
    setColor(status_id) {
      switch (status_id) {
        case 2:
          return (status_id = "secondary");
          break;
        case 3:
          return (status_id = "info text-white");
          break;
        case 4:
          return (status_id = "success");
          break;

        default:
          return (status_id = "dark");
          break;
      }
    },
  },
};
</script>

<style>
</style>