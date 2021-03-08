<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="faqs.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped shadow text-left">
          <thead class="thead-dark">
            <tr>
              <th class="text-center" scope="col"># Incidencia</th>
              <th scope="col">Departamento</th>
              <th scope="col">Asunto</th>
              <th class="text-center" scope="col">Fecha</th>
              <th class="text-center" scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="faq in faqs.data" :key="faq.id">
              <th scope="row" class="text-uppercase text-center">
                <router-link
                  :to="{ name: 'ticket.show', params: { ticketID: faq.id } }"
                >
                  {{ faq.custom_id }}
                </router-link>
              </th>
              <td>{{ faq.department.name }}</td>
              <td>{{ faq.subject_short }}</td>
              <td class="text-center">
                {{ faq.created_at | moment("DD-MM-YYYY HH:mm:ss") }}
              </td>
              <td>
                <router-link
                  :to="{ name: 'faqs.show', params: { id: faq.id } }"
                  class="btn btn-sm btn-block btn-success"
                >
                    <i class="fa fa-eye"></i>
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        :data="faqs"
        @pagination-change-page="emit_pagination"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
  </div>
</template>

<script>
export default {
  props: ["faqs", "searched"],
  methods: {
    emit_pagination(page) {
      this.searched.page = page;
      this.$emit("page", this.searched);
    },
  },
};
</script>

<style>
</style>