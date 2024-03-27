<template>
  <div class="pt-0 mt-4">
    <div class="card">
      <div class="card-header">
        <div class="row justify-content-center">
          <div class="mr-xl-auto col-12 col-xl-6">
            <h4 class="title text-uppercase" v-if="ticket">
              Incidencia:
              <span class="font-weight-bold">( {{ ticket.custom_id }} )</span>
            </h4>
            <!-- Solo mostrar en desktop -->
            <div class="col-xl-6 d-none d-xl-inline-block" v-show="ticket && user_role <= 4">
              <div class="input-group mt-2">
                <p class="mr-3 mt-1">
                  <span class="d-none d-xl-inline-block">Añadir a </span>
                  FAQ's
                </p>
                <label class="switch">
                  <input
                    type="checkbox"
                    v-model="ticket.knowledge_base"
                    @click="toggleFaqs"
                  />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
          </div>
          <!-- Solo mostrar en mobile -->
          <div class="mr-xl-auto col-12 col-xl-6 d-xl-none d-inline-block" v-show="ticket && user_role <= 4">
            <div class="input-group mt-2">
              <p class="mr-3 mt-1">
                <span class="d-none d-xl-inline-block">Añadir a </span>
                FAQ's
              </p>
              <label class="switch">
                <input
                  type="checkbox"
                  v-model="ticket.knowledge_base"
                  @click="toggleFaqs"
                />
                <span class="slider round"></span>
              </label>
            </div>
          </div>
          <div class="ml-xl-auto col-12 col-xl-6 mt-2" v-show="ticket && user_role <= 4">
            <div class="d-flex flex-row justify-content-xl-end">
              <div class="col-6 col-xl-4">
                <router-link
                  class="btn btn-sm btn-orange btn-block"
                  :to="{
                    name: 'ticket.edit',
                    params: { ticketID: ticketID },
                  }"
                >
                  Editar Incidencia
                </router-link>
              </div>
              <div class="col-6 col-xl-4">
                <router-link
                  class="btn btn-sm btn-secondary btn-block"
                  :to="{ name: 'ticket.index' }"
                >
                  Volver
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row justify-content-center">
          <div class="col-6 text-center">
            <div
              v-if="success.status === true"
              class="alert alert-success alert-dismissible fade show"
              role="alert"
            >
              {{ success.msg }}

              <button
                type="button"
                class="close"
                aria-label="Close"
                @click="success.status = false"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        </div>

        <ticket-view :ticket="ticket" :user_role="user_role" />
      </div>
    </div>

    <div class="row justify-content-end mx-1">
      <div v-for="attachment in ticket.attachments" :key="attachment.id">
        <a
          :href="`/api/download/ticket/${ticket.id}/file/${attachment.id}`"
          class="btn btn-sm btn-success shadow font-weight-bold mr-2 my-2"
        >
          {{ attachment.name ? attachment.name : attachment.path }}
        </a>
      </div>
    </div>

    <div class="d-none d-xl-block" v-show="ticket && user_role <= 4">
      <ticket-view-calls
        v-show="Object.keys(ticket.calls).length > 0"
        :calls="ticket.calls"
      ></ticket-view-calls>
    </div>

    <div class="d-xl-none d-block" v-show="ticket && user_role <= 4">
      <ticket-cards-calls
        v-show="Object.keys(ticket.calls).length > 0"
        :calls="ticket.calls"
      />
    </div>

    <ticket-comments
      v-if="ticket.comments ? ticket.comments.length > 0 : 0"
      :comments="ticket.comments"
      :user="user"
      :user_role="user_role"
      @succeeded="succeeded"
    >
    </ticket-comments>

    <ticket-new-coment
      :ticket_id="ticket.id"
      @load="get_ticket(ticket.id)"
    ></ticket-new-coment>
  </div>
</template>

<script>
export default {
  deactivated() {
    this.resetFields();
  },
  beforeRouteEnter(to, from, next) {
      // console.log(from)
    next(vm => {
      // console.log(from)
    })
  },
  props: ["ticketID", "user_role", "user"],
  data() {
    return {
      ticket: {
        user: {},
        customer: {},
        brand: {},
        car_model: {},
        calls: [],
        comments: [],
        attachments: [],
      },
      calls: [],
      selected: {
        calls: [],
      },
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
    };
  },
  activated() {
    if(!this.ticketID) {
      let routeArray = this.$route.params.pathMatch.split('/');
      this.get_ticket(routeArray[routeArray.length - 1]);
    } else {
      this.get_ticket(this.ticketID);
    }
      this.getCalls();
  },
  mounted() {
    if(!this.ticketID) {
      let routeArray = this.$route.params.pathMatch.split('/');
      this.get_ticket(routeArray[routeArray.length - 1]);
    } else {
      this.get_ticket(this.ticketID);
    }
  },
  methods: {
    closeAll() {
      this.success.status = false;
      this.error.status = false;
    },
    toggleFaqs() {
      this.closeAll();
      axios.get(`/api/toogle_faqs_ticket/${this.ticket.id}`).then((res) => {
        if (res.data.success) {
          this.success = {
            status: true,
            msg: res.data.msg,
          };
        } else if (res.data.error) {
          this.error = {
            status: true,
            msg: res.data.msg,
          };
        }
      });
    },
    succeeded(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success = {
        status: true,
        msg: data,
      };

      setTimeout(() => {
        this.get_ticket(this.ticket.id);
        this.success = {
          status: false,
          msg: "",
        };
      }, 1500);
    },
    get_ticket(ticket_id) {
      axios
        .get(`/api/ticket/${ticket_id}`)
        .then((res) => {
          this.ticket = res.data.ticket;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCalls() {
      axios
        .get("/api/get_all_calls")
        .then((res) => {
          this.calls = res.data.calls;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    selectedCalls(event) {
      this.selected.calls = event;
    },
    handleCalls() {
      axios
        .put("/api/ticket/" + parseInt(this.ticketID), {
          calls: this.selected.calls,
        })
        .then((res) => {
          if (res.data.success) {
            this.success.status = true;
            this.success.msg = res.data.msg;
            setTimeout(() => {
              window.location.href =
                "/admin/incidencia/" + parseInt(this.ticketID);
            }, 1500);
          }
        })
        .catch((err) => {
          console.log("err", err);
        });
    },
  },
};
</script>