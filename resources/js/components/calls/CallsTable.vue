<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="calls.total > 0">
      <div class="card-body">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr class="text-center text-uppercase">
              <th scope="col">INCIDENCIA</th>
              <th
                class="text-center"
                scope="col"
                v-if="[1, 2, 3, 4].includes(user_role)"
              >
                CLIENTE
              </th>
              <th scope="col">ORIGEN</th>
              <th scope="col">DESTINO</th>
              <th class="text-center" scope="col">FECHA</th>
              <th class="text-center" scope="col">INICIO</th>
              <th class="text-center" scope="col">
                DURACIÓN <small>(s)</small>
              </th>
              <th scope="col">TIPO</th>
              <th scope="col">ESTADO</th>
            </tr>
          </thead>
          <tr v-for="(call, idx) in calls.data" :key="idx" class="text-center">
            <td class="text-left">
              <router-link v-if="call.ticket" :to="{name: 'ticket.show', params: { 'ticketID': call.ticket.id } }">
                <button class="btn btn-link btn-sm text-uppercase">{{ call.ticket.custom_id }}</button>
              </router-link>
            </td>
            <td
              class="text-left"
              v-if="[1, 2, 3, 4].includes(user_role)"
            >
              {{ call.customer ? call.customer.comercial_name || call.customer.fiscal_name : null }}
            </td>
            <td>{{ call.src }}</td>

            <td v-if="call.is_incoming == 1">
              {{ call.dst_extension ? call.dst_extension : call.dst }}
            </td>
            <td v-else-if="type == 'internal'">{{ call.dst.slice(-3) }}</td>
            <td v-else>{{ call.dst ? call.dst : call.dst_extension }}</td>

            <!-- <td>{{ call.customer?.name }}</td> -->

            <td>{{ call.start | moment("DD-MM-YYYY") }}</td>
            <td>{{ call.start | moment("HH:mm:ss") }}</td>
            <td class="text-center">{{ call.duration }}</td>
            <td v-if="call.is_incoming == 1">
              <i class="text-success fa fa-sign-in-alt" title="Entrante"></i>
            </td>
            <td v-else>
              <i class="text-warning fa fa-sign-out-alt" title="Saliente"></i>
            </td>
            <td>{{ call.disposition }}</td>
          </tr>
          <tbody></tbody>
        </table>
      </div>
      <pagination
        :data="calls"
        @pagination-change-page="emit_pagination"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
    <div v-else class="mt-3 mx-3 shadow">
      <div class="alert alert-warning text-center">Haga una nueva búsqueda</div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user_role", "calls", "type", "searched", "mobile"],
  data() {
    return {};
  },
  mounted() {},
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