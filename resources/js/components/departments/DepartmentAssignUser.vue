<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between w-100">
                            <div class="mr-auto">
                                <h3 class="text-center text-success">
                                    ¿Que usuario(s) quieres asignar a este departamento?
                                </h3>
                            </div>
                            <div class="ml-auto">
                                <button type="button" class="close" @click="$emit('close')"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <department-assign-form @search="get_department_users" />
                        <div class="mt-3">
                            <department-assign-table :users="users" :searched="searched" :department="department" @page="get_department_users" />
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <slot name="footer ">
                            <button class="btn btn-sm btn-secondary text-uppercase" @click="$emit('close')">Salir</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: [
        'department'
    ],
    data() {
        return {
            users: [],
            searched: {},
        }
    },
    mounted() {
        this.get_department_users();
    },
    methods: {
        get_department_users(data) {
            data = data ? data : [];
            this.searched = data;

            axios.get('/api/get_department_users', {params: {
                    page: data.page,
                    name: data.name,
                    surname: data.surname,
                    email: data.email,
                    phone: data.phone,
                }
            }).then( res => {
                if(res.data.success) {
                    this.users = res.data.users
                }
                }).catch( err => {
                    console.log(err);
                });
        }
    }
}
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .1);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 800px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .11);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

</style>