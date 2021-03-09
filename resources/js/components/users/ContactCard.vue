<template>
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-center align-items-center">
        <h6 class="text-center text-uppercase m-0 p-0">{{ contact.name }}</h6>
      </div>
    </div>
    <div class="card-body">
      <user-edit-form :user="contact" :error="error" type="card" @error="$emit('error', error.errors)" @updated="emitUpdate" />
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
        errors: []
      }
    }
  },
  mounted() {
    // console.log({contact: this.contact});
  },
  methods: {
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
          console.log(res.data)
          if (res.data.success) {
            this.$emit("updated", res.data.msg);
          } else if (res.data.error) {
            this.error = {
              status: true,
              errors: res.data.errors
            };

            this.$emit("error", res.data.errors);
          }
          console.log("res", res.data);
        })
        .catch((err) => console.log(err));
    },
    emitUpdate(data) {
      this.$emit('updated', data);
    }
  },
};
</script>

<style>
</style>