<template>
  <div class="d-flex my-3 justify-content-start">
    <div class="d-inline-flex w-100">
      <!-- <div class="col-6 col-xl-2"> -->
        <button type="button" class="btn btn-sm btn-success font-weight-bold shadow mr-2" @click="exportFile('excel')">
          Exportar Excel
        </button>
      <!-- </div> -->
      <!-- <div class="col-6 col-xl-2"> -->
        <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold shadow" @click="exportFile('pdf')">
          Exportar PDF
        </button>
      <!-- </div> -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["toExport", "searched", "total"],
  methods: {
    exportFile(type) {
      this.searched.type = type;
      if(type === "pdf" && this.total > 300) {
        return this.$emit('error', [
          {'total': 'Fichero demasiado grande, por favor haga otra búsqueda mas concreta '}
        ]);
      }
      this.$emit('close');
      axios
        .get(`/api/export_${this.toExport}`, {
          responseType: "arraybuffer",
          params: this.searched,
        })
        .then((res) => {
          // console.log(res.data)
            // GET FILENAME FROM HEADERS
            var filename = "";
            var disposition = res.headers["content-disposition"];
            if (disposition && disposition.indexOf("attachment") !== -1) {
              var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
              var matches = filenameRegex.exec(disposition);
              if (matches != null && matches[1]) {
                filename = matches[1].replace(/['"]/g, "");
              }
            }
            // STORE FILE IN A BLOB
            let blob = new Blob([res.data], { type: "application/*" });
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            // DOWNLOAD IT
            link.click();
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>

<style>
</style>