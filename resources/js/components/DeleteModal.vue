<template>
  <transition name="modal">
    <div class="modal-mask" @click="$emit('close')">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <h3 class="text-center">
              ¿Seguro que deseas eliminar este {{ title }}?
            </h3>
          </div>
          <div class="modal-body">
            {{ name }} va a ser eliminado, haga click en Eliminar si lo desea.
          </div>
          <div class="modal-footer justify-content-center">
            <slot name="footer ">
              <button
                class="btn btn-sm btn-secondary text-uppercase"
                @click="$emit('close')"
              >
                Cancelar
              </button>
              <button
                class="btn btn-sm btn-danger text-uppercase"
                @click="$emit('getDeleted')"
              >
                Eliminar {{ title }}
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: ["data", "title"],
  computed: {
    name() {
      switch (this.title) {
        case "Cliente":
          return this.data.comercial_name
            ? this.data.comercial_name
            : this.data.fiscal_name;
          break;
        case "Ticket":
          return this.data.subject ? this.data.subject : this.data.custom_id;
          break;
        case "Post":
          return this.data.title ? this.data.title : 'Post';
          break;

        default:
          return this.data.name;
          break;
      }
    },
  },
};
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.1);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 500px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.11);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #e02d1a;
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