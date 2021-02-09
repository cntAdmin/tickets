<template>
    <form @submit.prevent="handleSubmit">
        <div class="form-row">
            <div class="form-inline">
                <div class="form-group col-12 col-md-6">
                    <label class="sr-only" for="dateFrom">Nombre</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Nombre</div>
                        </div>
                        <input type="text" v-model="search.name" class="form-control"
                            title="Minimo 3 caracteres" autofocus />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label class="sr-only" for="dateFrom">Apellidos</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Apellidos</div>
                        </div>
                        <input type="text" v-model="search.surname" class="form-control"
                            title="Minimo 3 caracteres" autofocus />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 mt-2">
                    <label class="sr-only" for="dateFrom">Email</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Email</div>
                        </div>
                        <input type="text" v-model="search.email" class="form-control"
                            title="Minimo 3 caracteres" autofocus />
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 mt-2">
                    <label class="sr-only" for="dateFrom">Teléfono</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-uppercase">Teléfono</div>
                        </div>
                        <input type="text" v-model="search.phone" class="form-control"
                            title="Minimo 3 caracteres" autofocus />
                    </div>
                </div>
            </div>
        </div>
            <div class="row justify-content-center mt-3 mx-3">
                <button class="btn btn-sm btn-block btn-success">
                    Buscar
                </button>
            </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            search: {
                page: 1,
                name: '',
                surname: '',
                email: '',
                phone: ''
            }

        }
    },
    methods: {
        handleSubmit() {
            this.search.page = 1;
            this.$emit('search', this.search);
        }
    }
}
</script>

<style>

</style>