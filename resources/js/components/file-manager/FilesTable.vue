<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="files.total > 0">
      <div class="card-body">
        <div class="alert alert-dismissable alert-success" v-if="success.status === true">
            {{ success.msg }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click.prevent="success.status = false">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-dismissable alert-danger" v-if="error.status === true">
            {{ error.msg }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click.prevent="error.status = false">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="d-flex justify-content-start my-3">
            <div class="form-inline">
                <button class="btn btn-sm btn-danger text-uppercase font-weight-bold" @click.prevent="openDeleteAllModal" >
                    Eliminar todos ficheros de esta búsqueda
                </button>
                <button class="btn btn-sm btn-danger ml-3 text-uppercase font-weight-bold" v-if="Object.keys(filesToDelete).length > 0" @click.prevent="deleteSelected">
                    Eliminar seleccionados <span class="ml-3">{{Object.keys(filesToDelete).length}}</span>
                </button>
            </div>
        </div>

        <table class="table table-hover table-striped shadow text-left">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Seleccionar</th>
              <th scope="col"># Ticket Asociado</th>
              <th scope="col">Miniatura</th>
              <th scope="col">Nombre Fichero</th>
              <th scope="col">Fecha Subida</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="file in files.data" :key="file.id">
                <td class="align-self-center">
                    <input type="checkbox" :name="file.id" @click="handleCheckbox">
                </td>
                <th scope="row" class="align-self-center">{{ file.comments[0].ticket.custom_id }}</th>
                <td class="">
                    <img :src="`/storage/${file.path}`" :alt="file.name" class="w-25 img-fluid img-thumbnail"
                        @click.prevent="openImageModal(file)">
                </td>
                <td class="align-self-center">{{ file.name }}</td>
                <td class="align-self-center">{{ file.created_at | moment('DD-MM-YYYY') }}</td>
                <td class="align-self-center">
                    <button class="btn btn-sm btn-danger" title="Borrar Fichero" @click="openModal(file)"><i class="fa fa-trash-alt"></i></button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        :data="files"
        @pagination-change-page="emit_pagination"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
    <div v-else class="mt-3 shadow">
      <div class="alert alert-warning text-center">Haga una nueva búsqueda</div>
    </div>
    <delete-modal
      v-show="showDeleteModal"
      :data="file"
      title="Fichero"
      @getDeleted="getDeleted"
      @close="showDeleteModal = false"
    ></delete-modal>
    <delete-modal
      v-show="showDeleteAllModal"
      :data="file"
      title="Ficheros"
      @getDeleted="deleteAll"
      @close="showDeleteAllModal = false"
    ></delete-modal>
    <image-modal :file="file"
      v-if="showImageModal"
      @close="showImageModal = false"
    ></image-modal>
  </div>
</template>

<script>
export default {
    props: ['files', 'searched'],
    data() {
        return {
            filesToDelete: [],
            file: {},
            showDeleteModal:false,
            showDeleteAllModal:false,
            showImageModal:false,
            success: {
                status: false,
                msg: ''
            },
            error: {
                status: false,
                msg: ''
            }
        }
    },
    methods: {
        openImageModal(file) {
            this.file = file;
            this.showImageModal = true;
        },
        openDeleteAllModal() {
            this.showDeleteAllModal = true
        },
        deleteAll(){
            axios.post('api/deleteAllFiles', {
                ticket_id: this.searched.ticket_id,
                name: this.searched.name,
                in_faqs: this.searched.inFaqs
            }).then( res => {
                if(res.data.success) {
                    this.showDeleteAllModal = false,
                    this.success = {
                        status:true,
                        msg: res.data.msg
                    }
                }
            }).catch( err => console.log(err));
        },
        deleteSelected() {
            axios.post('/api/deleteSelectedFiles', {
                selected: this.filesToDelete
            }).then( res => {
                if(res.data.success) {
                    // this.showDeleteAllModal = false,
                    this.success = {
                        status:true,
                        msg: res.data.msg
                    }
                } else if(res.data.error) {
                    this.error = {
                        status: true,
                        msg: res.data.msg
                    }

                }
            }).catch( err => console.log(err));
        },
        getDeleted() {
            axios.delete(`/api/file-manager/${this.file.id}`)
                .then( res => {
                    console.log(res.data)
                    if(res.data.success) {
                        this.success = {
                            status:true,
                            msg: res.data.msg
                        }
                    }
            }).catch( err => console.log(err));
        },
        handleCheckbox(e) {
            if(e.target.checked) {
                this.filesToDelete.push(e.target.name)
            } else {
                this.filesToDelete.splice(this.filesToDelete.indexOf(e.target.name),1)
            }
        },
        openModal(file) {
            this.file = file;
            this.showDeleteModal = true;
        },
        emit_pagination(page) {
            this.searched.page = page;
            this.$emit('page', this.searched)
        },
    }
};
</script>