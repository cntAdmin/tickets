<template>
  <div class="pt-0 mt-4">
    <div class="card">
      <div class="card-header">
        <div class="row justify-content-center">
          <div class="mr-xl-auto col-12 col-xl-6">
            <h4 class="title text-uppercase" v-show="ticket">
              Incidencia:
              <span class="font-weight-bold">( {{ ticket.custom_id }} )</span>
            </h4>
            <!-- Solo mostrar en desktop -->
          </div>
          <!-- Solo mostrar en mobile -->
          <div class="ml-xl-auto col-12 col-xl-6 mt-2" v-show="ticket">
            <div class="d-flex flex-row justify-content-xl-end">
              <div class="col-6 col-xl-4">
                <router-link
                  class="btn btn-sm btn-secondary btn-block"
                  :to="{ name: 'faqs.index' }"
                >
                  Volver
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-view :ticket="ticket" :isFaq="true" />
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

    <ticket-comments
      v-if="ticket.comments ? ticket.comments.length > 0 : 0"
      :comments="ticket.comments"
      :user="user"
      :isFaq="true"
      :user_role="user_role"
    >
    </ticket-comments>
  </div>
</template>

<script>
export default {
  props: ["id","user_role", "user"],
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
    };
  },
  activated() {
    this.get_ticket();
  },
  methods: {
    get_ticket() {
        // console.log(this.id)
      axios
        .get(`/api/ticket/${this.id}`)
        .then((res) => {
          this.ticket = res.data.ticket;
        })
        .catch((err) => {
          console.log(err);
        });
    },

  }
};
</script>

<style>
</style>