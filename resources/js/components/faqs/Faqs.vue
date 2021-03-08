<template>
  <div class="d-flex flex-column justify-content-center mx-lg-3">
    <div
      class="d-flex flex-row justify-content-center align-items-center bg-blue-gradient w-100 shadow"
      style="height: 100px"
    >
      <h2 class="font-weight-bolder text-white text-shadow-md text-uppercase">AAP-TC Glosario de Incidencias</h2>
    </div>
    <faqs-search-form @search="getFaqs" />

    <transition name="fade" v-if="searching" mode="out-in">
      <div class="d-flex justify-content-center my-5">
        <spinner></spinner>
      </div>
    </transition>
    <transition name="fade" v-else-if="faqs.data" mode="out-in">
      <div v-if="faqs.total > 0">
        <div class="d-none d-xl-block">
          <!-- DESCKTOP TABLE -->
          <faqs-table
            class="d-none d-xl-block"
            :faqs="faqs"
            :searched="searched"
            @page="getFaqs"
          />
        </div>
        <!-- MOBILE CARDS TABLE TABLE -->
        <div class="d-xl-none d-block">
          <mobile-faqs-cards-table
            :faqs="faqs.data"
          />
        </div>
      </div>
      <div v-else class="mt-3 shadow">
        <div class="alert alert-warning text-center mb-0">
          Haga una nueva búsqueda
        </div>
      </div>
    </transition>

  </div>
</template>

<script>
export default {
  data() {
    return {
      faqs: [],
      searched: {},
      searching: false,
    };
  },
  mounted() {
    this.getFaqs();
  },
  methods: {
    getFaqs(data) {
      if (!this.searching) {
        this.searching = true;
        axios
          .get("/api/faqs", {
            params: {
              page: data ? data.page : null,
              text: data ? data.text : null,
            },
          })
          .then((res) => {
            if (res.data.success) {
              this.searching = false;
              this.faqs = res.data.faqs;
            }
          });
      }
    },
  },
};
</script>

<style>
</style>