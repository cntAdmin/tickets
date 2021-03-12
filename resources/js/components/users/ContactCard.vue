<template>
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div class="col-9">
          <div class="d-flex justify-content-start">
            <h5 class="text-center text-uppercase m-0 p-0 text-center font-weight-bold">
              {{ contact.name }}
            </h5>
          </div>
        </div>
        <div class="col-3">
          <div class="d-flex justify-content-end">
            <label class="switch">
              <input
                v-model="contact.is_active"
                type="checkbox"
                @click="toggleActive($event)"
              />
              <span class="slider round"></span>
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <user-edit-form
        :user="contact"
        :error="error"
        type="card"
        @error="$emit('error', error.errors)"
        @updated="emitUpdate"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["contact"],
  data() {
    return {
      error: {
        status: false,
        errors: [],
      },
    };
  },
  mounted() {
    // console.log({contact: this.contact});
  },
  methods: {
    toggleActive(e) {
      axios
        .put(`/api/toggle_active_user/${this.contact.id}`, {
          is_active: e.target.checked,
        })
        .then((res) => {
          if (res.data.success) {
            // console.log(res.data.msg)
          }
        });
    },
    handleSubmit() {
      this.$emit("closeAll");
      axios
        .put(`/api/user/${this.contact.id}`, {
          customer_id: this.contact.customer_id,
          department_id: this.contact.department_id,
          name: this.contact.name,
          surname: this.contact.surname,
          username: this.contact.username,
          phone: this.contact.phone,
          email: this.contact.email,
          password: this.contact.password,
          password_confirmation: this.contact.password_confirmation,
          is_active: this.contact.is_active,
        })
        .then((res) => {
          // console.log(res.data);
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
          } else if (res.data.error) {
            this.error = {
              status: true,
              errors: res.data.errors,
            };

            this.$emit("error", res.data.errors);
          }
        })
        .catch((err) => console.log(err));
    },
    emitUpdate(data) {
      this.$emit("updated", data);
    },
  },
};
</script>

<style>
</style>