<template>
  <div class="flex-row justify-content-center" id="cards-list">
    <div class="card mt-3 shadow" v-for="ticket in faqs" :key="ticket.id">
      <div class="card-header">
        <h4 class="text-uppercase text-left font-weight-bold">
          <router-link
            :to="{
              name: 'faqs.show',
              params: { id: ticket.id },
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
                class="disabled col-4 btn btn-sm btn-block"
                type="button"
                :title="ticket.status.name"
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
              <div class="col-12 px-2 py-0">
                <router-link
                  :to="{
                    name: 'faqs.show',
                    params: { id: ticket.id },
                  }"
                  class="btn btn-sm btn-success btn-block"
                >
                  <i class="fa fa-eye"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END OF CARD FOOTER -->
    </div>
    <!-- END OF CARD -->
    <transition name="fade" v-if="searching" mode="out-in">
      <div class="row justify-content-center mt-5">
        <spinner></spinner>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ["faqs"],
  data() {
    return {
      searching: false
    }
  },
  methods: {
    
  },
};
</script>

<style>
</style>