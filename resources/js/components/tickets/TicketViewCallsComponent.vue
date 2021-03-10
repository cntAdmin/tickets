<template>
  <div class="card mt-2">
    <div class="card-header">Llamadas asociadas</div>
    <div class="card-body">
      <table class="table table-hover table-striped">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Tipo</th>
            <th scope="col">Origen</th>
            <th scope="col">Destino</th>
            <th scope="col">Fecha</th>
            <th scope="col">Duración <sub>(s)</sub></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(call, idx) in calls" :key="idx">
            <td>{{ call.is_incoming ? "Entrante" : "Saliente" }}</td>
            <td>{{ call.src }}</td>
            <td v-if="call.is_incoming == 1">
              {{ call.dst_extension ? call.dst_extension : call.dst }}
            </td>
            <td v-else-if="type == 'internal'">{{ call.dst.slice(-3) }}</td>
            <td v-else>{{ call.dst ? call.dst : call.dst_extension }}</td>
            <td>{{ call.start | moment("L LTS") }}</td>
            <td>{{ call.billsec }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: ["calls"],
};
</script>

<style>
</style>