<template>
  <div class="card mt-3">
    <div class="card-header">Llamadas asociadas</div>
    <div class="card-body">
      <table class="table table-hover table-striped">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Tipo</th>
            <th scope="col">Origen</th>
            <th scope="col">Destino</th>
            <th scope="col">Fecha</th>
            <th scope="col">Duración</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(call, idx) in calls" :key="idx">
            <td>{{ call.is_incoming ? "Entrante" : "Saliente" }}</td>
            <td>{{ call.src }}</td>
            <td>{{ call.dst }}</td>
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