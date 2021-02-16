<template>
  <div class="mx-3">
    <div class="row">
      <card-counter
        title="Total"
        color="primary"
        :count="files.data ? files.total : 0"
        icon="clipboard-list"
        size="4"
      />
      <card-counter
        title="En FAQ\'s"
        color="danger"
        :count="inFaqs"
        icon="envelope-open"
        size="4"
      />
      <card-counter
        title="Sin publicar"
        color="info"
        :count="files.data ? files.total - inFaqs : 0"
        icon="times-circle"
        size="4"
      />
    </div>

    <files-search-form @search="getFiles"></files-search-form>

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
    <transition name="fade" v-else-if="files.data" mode="out-in">
      <files-table
        :files="files"
        :searched="searched"
        @page="getFiles"
        @deleted="hasBeenDeleted"
      />
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      files: [],
      searched: {},
      inFaqs: 0,
      searching: false,
      deleted: {
        status: false,
        msg: "",
      },
    };
  },
  methods: {
    hasBeenDeleted(data) {
      this.deleted.status = true;
      this.deleted.msg = data;

      setTimeout(() => {
          this.getTickets()
      }, 1500);
    },
    getFiles(data) {
      this.searching = true;
      this.searched = data ? data : this.searched;
      this.getCounters();
      axios
        .get("/api/file-manager", {
          params: {
            page: data ? data.page : null,
            ticket_id: data ? data.ticket_id : null,
            name: data ? data.name : null,
            in_faqs: data ? data.inFaqs : null,
          },
        })
        .then((res) => {
          this.files = res.data.files;
          this.searching = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCounters() {
      axios.get('/api/get_files_counters')
        .then( res => {
          this.inFaqs = res.data.inFaqs ? res.data.inFaqs : 0;
        })
    }
  },
};
</script>