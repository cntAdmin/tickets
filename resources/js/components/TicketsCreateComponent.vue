<template>
<div class="w-100">
    <div class="card mt-4">
        <div class="card-body">
            <div v-show="success.value" class="alert alert-success alert-dismissible fade show text-center">
                {{ success.message }}
            </div>
            <div v-show="error" class="alert alert-danger alert-dismissible fade show">
                <div v-for="(error, title) in errors" :key="title">
                    <p v-for="(err, idx) in error" :key="idx">
                        {{ err }}
                    </p>
                    <button type="button" class="close" @click="remove_errors()">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
            <form @submit="handleSubmit" method="POST">
                <div class="form-inline">
                    <div class="form-group col-12 col-md-6 col-lg-4">
                        <label class="sr-only" for="dateFrom">Cliente</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Cliente</div>
                            </div>
                            <select class="form-control" v-model="selected.customer_id" @change="get_all_users()" required>
                                <option value="" disabled>-- SELECCIONE UN CLIENTE --</option>
                                <option v-for="cs in customers" :key="cs.id" :value="cs.id">
                                    {{ cs.comercial_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4">
                        <label class="sr-only" for="dateFrom">Contacto</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Contacto</div>
                            </div>
                            <select class="form-control" v-model="selected.user_id" required>
                                <option value="" disabled>-- SELECCIONE UN USUARIO --</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">
                                    {{ user.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4">
                        <label class="sr-only" for="dateFrom">Departamentos</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Departamentos</div>
                            </div>
                            <select class="form-control" v-model="selected.department_id">
                                <option v-for="dpt in departments" :key="dpt.id" :value="dpt.id">
                                    {{ dpt.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Nº Bastidor</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Nº Bastidor</div>
                            </div>
                            <input :class="[errors.frame_id ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.frame_id"/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Matrícula</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Matrícula</div>
                            </div>
                            <input :class="[errors.plate ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.plate"/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Tipo de Motor</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Tipo de Motor</div>
                            </div>
                            <input :class="[errors.engine_type ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.engine_type">
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Marca</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Marca</div>
                            </div>
                            <input :class="[errors.brand ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.brand"/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Modelo</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Modelo</div>
                            </div>
                            <input :class="[errors.model ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.model"/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
                        <label class="sr-only" for="dateFrom">Solicito</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Solicito</div>
                            </div>
                            <input :class="[errors.ask_for ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.ask_for" required/>
                        </div>
                    </div>
                </div>
                <div class="form-inline mt-2">
                    <div class="form-group col-12 col-md-6">
                        <label class="sr-only" for="dateFrom">Asunto</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Asunto</div>
                            </div>
                            <input :class="[errors.subject ? 'is-invalid': ''] + ' form-control'" type="text" v-model="selected.subject"  required/>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <button type="button" :class="'btn btn-block text-white ' + (selected.calls.length >= 1 ? 'btn-danger' : 'btn-info') " @click="toggleModal"
                            data-toggle="modal" data-target="#assignCall">{{ selected.calls.length >= 1 ? 'Llamadas seleccionada(s)' : 'Asignar Llamadas'}}</button>
                    </div>
                    <calls-modal :calls="calls" @selectedCalls="selectedCalls($event)"></calls-modal>

                </div>
                <div class="form-inline mt-2">
                    <div class="form-group col-12">
                        <label class="sr-only" for="dateFrom">Descripcion</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div :class="[errors.description ? 'bg-danger text-white': ''] + ' input-group-text text-uppercase'">Descripcion</div>
                            </div>
                        </div>
                        <ejs-richtexteditor ref="description" :quickToolbarSettings="quickToolbarSettings" :height="400"
                            :toolbarSettings="toolbarSettings">
                        </ejs-richtexteditor>
                    </div>
                </div>
                <div class="form-inline mt-2">
                    <div class="form-group col-12">
                        <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div :class="[errors.tests_done ? 'bg-danger text-white': ''] + ' input-group-text text-uppercase'">Pruebas Realizadas</div>
                            </div>
                        </div>
                        <ejs-richtexteditor ref="tests_done" :quickToolbarSettings="quickToolbarSettings" :height="400"
                            :toolbarSettings="toolbarSettings">
                        </ejs-richtexteditor>
                    </div>
                </div>
                <div class="form-inline mt-4">
                    <button type="submit" class="btn btn-success btn-block mx-3">Enviar Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
import { RichTextEditorPlugin, Toolbar, Image, Link, HtmlEditor, QuickToolbar } from "@syncfusion/ej2-vue-richtexteditor";

Vue.use(RichTextEditorPlugin);

export default {
    provide: {
        richtexteditor:[Toolbar, Image, Link, HtmlEditor, QuickToolbar]
    },
    props: [
    ],
    data() {
        return {
            users: {},
            customers: {},
            departments: {},
            calls: {},
            selected: {
                customer_id: '',
                user_id: '',
                department_id: '',
                frame_id: '',
                plate: '',
                brand: '',
                model: '',
                engine_type: '',
                ask_for: '',
                description: '',
                tests_done: '',
                calls: [],
            },
            success: {
                value: false,
                message:''
            },
            error:false,
            errors: {},
            quickToolbarSettings: {
                image: [
                    'Replace', 'Align', 'Caption', 'Remove', 'InsertLink', 'OpenImageLink', '-',
                    'EditImageLink', 'RemoveImageLink', 'Display', 'AltText', 'Dimension',
                ]
            },
            toolbarSettings: {
                items: [
                    'Bold', 'Italic', 'Underline', 'StrikeThrough',
                    'FontName', 'FontSize', 'FontColor', 'BackgroundColor',
                    'LowerCase', 'UpperCase', '|',
                    'Formats', 'Alignments', 'OrderedList', 'UnorderedList',
                    'Outdent', 'Indent', '|',
                    'CreateLink', 'Image', '|', 'ClearFormat', 'Print',
                    'SourceCode', 'FullScreen', '|', 'Undo', 'Redo'
                ]
            },
            modal: false,
            buttonText: 'Asignar llamada(s)'
        }
    },
    beforeMount() {
        this.get_all_departments();
        this.get_all_customers();
    },
    methods: {
        get_all_departments() {
            axios.get('/get_all_departments')
            .then(res => {
                this.departments = res.data;
                this.selected.department_id = this.departments[0].id
            }).catch(err => {
                console.log(err)
            });
        },
        get_all_customers() {
            axios.get('/get_all_customers')
            .then(res => {
                this.customers = res.data;
            }).catch(err => {
                console.log(err)
            });
        },
        get_all_users() {
            if(this.selected.customer_id !== '') {
                axios.get('/get_all_users', { params: {
                        customer_id: this.selected.customer_id
                    }
                }).then(res => {
                    this.users = res.data;
                }).catch(err => {
                    console.log(err)
                });
            }
        },
        handleSubmit(e) {
            e.preventDefault();
            this.errors = {};

            if(this.selected.frame_id === "" && this.selected.plate === "" ) {
                this.error = true;
                this.errors['frame_id'] = ['Uno de los campos nº bastido o matrícula es obligatorio.'];
                this.errors['plate'] = [''];
                return this.errors;
            }
            this.success.value = false;
            this.error = false;
            this.errors = {};

            axios.post('/ticket', {
                customer_id: this.selected.customer_id,
                user_id: this.selected.user_id,
                department_id: this.selected.department_id,
                frame_id: this.selected.frame_id,
                plate: this.selected.plate,
                brand: this.selected.brand,
                model: this.selected.model,
                engine_type: this.selected.engine_type,
                ask_for: this.selected.ask_for,
                subject: this.selected.subject,
                description: this.$refs.description.ej2Instances.value,
                tests_done: this.$refs.tests_done.ej2Instances.value,
                calls: this.selected.calls
            }).then(res => {
                console.log(res.data)
                if(res.data.success) {
                    this.success.value = true;
                    this.success.message = res.data.success;

                    setTimeout(() => {
                        window.location.href = '/ticket';
                    }, 1500);
                } else if (res.data.error) {
                    this.error = true;
                    this.errors = res.data.error;
                }
            }).catch(err => {
                console.log(err)
            });
        },
        remove_errors() {
            this.error = true;
            this.errors = {};
        },
        toggleModal() {
            axios.get('/get_all_calls')
            .then(res => {
                this.calls = res.data.calls;
            }).catch(err => {
                console.log(err);
            })
        },
        selectedCalls(event) {
            this.selected.calls = event;
            console.log(this.selected.calls);
        }
    }, // END METHODS
}
</script>
<style lang="css">
    @import "../../../node_modules/@syncfusion/ej2-base/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-inputs/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-lists/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-popups/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-buttons/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-navigations/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-splitbuttons/styles/material.css";
    @import "../../../node_modules/@syncfusion/ej2-vue-richtexteditor/styles/material.css";
</style>