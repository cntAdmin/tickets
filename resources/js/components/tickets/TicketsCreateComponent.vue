<template>
  <div class="w-100">
    <div class="card mt-4">
      <div class="card-body">
        <div
          v-if="success.status"
          class="alert alert-success alert-dismissible fade show text-center"
        >
          {{ success.msg }}
        </div>
        <div
          v-if="error.status"
          class="alert alert-danger alert-dismissible fade show text-center"
        >
          <form-errors
            :errors="error.errors"
            @close="error.status = false"
          ></form-errors>
        </div>
        <form
          id="create_ticket_form"
          @submit.prevent="handleSubmit"
          method="POST"
        >
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Cliente</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">
                    Cliente
                  </div>
                </div>
                <vue-select
                  v-if="!is_admin"
                  class="col-8 col-xl-9 px-0 w-100"
                  ref="customersSelect"
                  transition="vs__fade"
                  label="comercial_name"
                  itemid="id"
                  v-model="customer.comercial_name"
                  disabled
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.custom_id }} -
                    {{
                      option.comercial_name
                        ? option.comercial_name
                        : cs.fiscal_name
                    }}
                  </template>
                </vue-select>
                <vue-select
                  v-else
                  :class="
                    [error.errors.comercial_name ? 'is-invalid' : ''] +
                    ' col-8 col-xl-9 px-0 w-100'
                  "
                  :options="customers"
                  ref="customersSelect"
                  transition="vs__fade"
                  label="comercial_name"
                  itemid="id"
                  @input="setCustomer"
                  v-model="customer.comercial_name"
                ></vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Contacto</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Contacto</div>
                </div>
                <select
                  v-if="!is_admin"
                  class="form-control"
                  :value="user.id"
                  disabled
                >
                  <option :value="user.id">{{ user.name }}</option>
                </select>
                <select
                  v-else
                  :class="
                    [error.errors.user_id ? 'is-invalid' : ''] + ' form-control'
                  "
                  v-model="selected.user_id"
                  required
                >
                  <option value="" disabled>-- SELECCIONE UN USUARIO --</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Departamentos</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Departamentos
                  </div>
                </div>
                <select
                  :class="
                    [error.errors.department_id ? 'is-invalid' : ''] +
                    ' form-control'
                  "
                  v-model="selected.department_id"
                >
                  <option
                    v-for="dpt in departments"
                    :key="dpt.id"
                    :value="dpt.id"
                  >
                    {{ dpt.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Nº Bastidor</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nº Bastidor</div>
                </div>
                <input
                  :class="
                    [error.errors.frame_id ? 'is-invalid' : ''] +
                    ' form-control'
                  "
                  type="text"
                  v-model="selected.frame_id"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-1">
              <label class="sr-only" for="dateFrom">Matrícula</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Matrícula</div>
                </div>
                <input
                  :class="
                    [error.errors.plate ? 'is-invalid' : ''] + ' form-control'
                  "
                  type="text"
                  v-model="selected.plate"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Tipo de Motor</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Tipo de Motor
                  </div>
                </div>
                <input
                  :class="
                    [error.errors.engine_type ? 'is-invalid' : ''] +
                    ' form-control'
                  "
                  type="text"
                  v-model="selected.engine_type"
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Marca</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Marca</div>
                </div>
                <vue-select
                  :class="
                    [error.errors.engine_type ? 'is-invalid' : ''] +
                    ' col-8 col-xl-9 px-0 mx-0'
                  "
                  :options="brands"
                  ref="brandsSelect"
                  transition="vs__fade"
                  label="name"
                  itemid="id"
                  @input="setBrand"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Modelo</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Modelo</div>
                </div>
                <vue-select
                  :options="models"
                  ref="modelsSelect"
                  class="col-8 col-xl-9 px-0 mx-0"
                  transition="vs__fade"
                  label="name"
                  itemid="id"
                  @input="setModel"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <label class="sr-only" for="dateFrom">Otro Marca/Modelo</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Otro Marca/Modelo
                  </div>
                </div>
                <input
                  :class="
                    [error.errors.other_brand_model ? 'is-invalid' : ''] +
                    ' form-control'
                  "
                  type="text"
                  v-model="selected.other_brand_model"
                  v-if="!selected.model_id"
                />
                <input v-else class="form-control" type="text" disabled />
              </div>
            </div>
          </div>
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 mt-2">
              <label class="sr-only" for="dateFrom">Asunto</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Asunto</div>
                </div>
                <input
                  :class="
                    [error.errors.subject ? 'is-invalid' : ''] + ' form-control'
                  "
                  type="text"
                  v-model="selected.subject"
                  required
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 mt-2">
              <label class="sr-only" for="dateFrom">Solicito</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Solicito</div>
                </div>
                <input
                  :class="
                    [error.errors.ask_for ? 'is-invalid' : ''] + ' form-control'
                  "
                  type="text"
                  v-model="selected.ask_for"
                  required
                />
              </div>
            </div>
            <calls-modal @selectedCalls="selectedCalls($event)"></calls-modal>
          </div>
          <div class="form-inline">
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Descripcion</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div
                    :class="
                      [error.errors.description ? 'bg-danger text-white' : ''] +
                      ' input-group-text text-uppercase'
                    "
                  >
                    Descripcion
                  </div>
                </div>
              </div>
              <ejs-richtexteditor
                ref="description"
                :quickToolbarSettings="quickToolbarSettings"
                :height="400"
                :toolbarSettings="toolbarSettings"
              >
              </ejs-richtexteditor>
            </div>
          </div>
          <div class="form-inline">
            <div class="form-group col-12 mt-2">
              <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div
                    :class="
                      [error.errors.tests_done ? 'bg-danger text-white' : ''] +
                      ' input-group-text text-uppercase'
                    "
                  >
                    Pruebas Realizadas
                  </div>
                </div>
              </div>
              <ejs-richtexteditor
                ref="tests_done"
                :quickToolbarSettings="quickToolbarSettings"
                :height="400"
                :toolbarSettings="toolbarSettings"
              >
              </ejs-richtexteditor>
            </div>
          </div>
          <div class="form-inline mx-3">
            <div class="form-group mt-2 w-100">
              <label class="sr-only" for="dateFrom">Adjuntar Fichero(s)</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    <span class="d-none d-xl-block">Ficheros</span> adjuntos
                  </div>
                </div>
                <input
                  class="form-control"
                  type="file"
                  @change="uploadFile"
                  multiple
                />
              </div>
            </div>
          </div>
          <div class="form-inline mt-4">
            <button
              form="create_ticket_form"
              type="submit"
              class="btn btn-success btn-block mx-3"
            >
              Enviar Incidencia
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Toolbar,
  Image,
  Link,
  HtmlEditor,
  QuickToolbar,
} from "@syncfusion/ej2-vue-richtexteditor";

export default {
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },
  deactivated() {
    this.resetFields();
  },
  props: ["customer_id", "user_role", "user"],
  data() {
    return {
      users: [],
      customers: [],
      customer: {},
      brands: [],
      models: [],
      departments: {},
      calls: {},
      selected: {
        customer_id: "",
        user_id: "",
        department_id: "",
        frame_id: "",
        plate: "",
        brand_id: "",
        model_id: "",
        other_brand_model: "",
        engine_type: "",
        ask_for: "",
        description: "",
        tests_done: "",
        calls: [],
      },
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
      quickToolbarSettings: {
        image: [
          "Replace",
          "Align",
          "Caption",
          "Remove",
          "InsertLink",
          "OpenImageLink",
          "-",
          "EditImageLink",
          "RemoveImageLink",
          "Display",
          "AltText",
          "Dimension",
        ],
      },
      toolbarSettings: {
        items: [
          "Bold",
          "Italic",
          "Underline",
          "StrikeThrough",
          "FontName",
          "FontSize",
          "FontColor",
          "BackgroundColor",
          "LowerCase",
          "UpperCase",
          "|",
          "Formats",
          "Alignments",
          "OrderedList",
          "UnorderedList",
          "Outdent",
          "Indent",
          "|",
          "CreateLink",
          "Image",
          "|",
          "ClearFormat",
          "Print",
          "SourceCode",
          "FullScreen",
          "|",
          "Undo",
          "Redo",
        ],
      },
      modal: false,
      buttonText: "Asignar llamada(s)",
      files: [],
      is_admin: false,
      admin_roles: [
        1, 2, 3, 4
      ]
    };
  },
  activated() {
    this.is_admin = this.admin_roles.some(
      (role) => role === this.user.roles[0].id
    );
    if (!this.is_admin) {
      this.customer.comercial_name = this.user.customer.comercial_name;
      this.selected.customer_id = this.user.customer.id;
      this.selected.user_id = this.user.id;
      this.get_all_users();
    }
    this.get_all_departments();
    this.get_all_customers();
    this.get_all_brands();
    if (this.$route.params.customer_id) {
      axios
        .get("/api/customer/" + this.$route.params.customer_id)
        .then((res) => {
          this.customer = res.data.customer;
          this.get_all_users();
        });
    }
  },
  methods: {
    uploadFile(e) {
      this.files = e.target.files;
    },

    setModel(value) {
      this.selected.model_id = value ? value.id : null;
    },
    setBrand(value) {
      this.selected.brand_id = value ? value.id : null;
      if (value) {
        axios
          .get("/api/brand/" + this.selected.brand_id + "/model")
          .then((res) => {
            this.models = res.data.models;
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    setCustomer(value) {
      this.selected.customer_id = value ? value.id : null;
      if (value) {
        this.get_all_users();
      }
    },
    get_all_brands() {
      axios
        .get("/api/get_all_brands")
        .then((res) => {
          this.brands = res.data.brands;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_all_departments() {
      axios
        .get("/api/get_all_departments")
        .then((res) => {
          this.departments = res.data.departments;
          this.selected.department_id = this.departments[0].id;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_all_customers() {
      axios
        .get("/api/get_all_customers")
        .then((res) => {
          this.customers = res.data.customers;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    get_all_users() {
      if (this.selected.customer_id !== "" || this.$route.params.customer_id) {
        axios
          .get("/api/get_all_users", {
            params: {
              customer_id: this.$route.params.customer_id
                ? this.customer.id
                : this.selected.customer_id,
            },
          })
          .then((res) => {
            this.users = res.data.users;
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },
    handleSubmit() {
      if (this.selected.frame_id === "" && this.selected.plate === "") {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        this.error.status = true;
        this.error.errors = { frame_id: [], plate: [] };

        this.error.errors["frame_id"].push(
          "Uno de los campos nº bastido o matrícula es obligatorio."
        );

        return this.error.errors;
      }

      this.success = {
        status: false,
      };
      this.error = {
        status: false,
        errors: [],
      };

      const formData = new FormData();
      if (this.files) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
      }
      formData.append("customer_id", this.selected.customer_id);
      formData.append("user_id", this.selected.user_id);
      formData.append("department_id", this.selected.department_id);
      formData.append("frame_id", this.selected.frame_id);
      formData.append("plate", this.selected.plate);
      formData.append("brand_id", this.selected.brand_id);
      formData.append("model_id", this.selected.model_id);
      formData.append("other_brand_model", this.selected.other_brand_model);
      formData.append("engine_type", this.selected.engine_type);
      formData.append("ask_for", this.selected.ask_for);
      formData.append("subject", this.selected.subject);
      formData.append("description", this.$refs.description.ej2Instances.value);
      formData.append("tests_done", this.$refs.tests_done.ej2Instances.value !== null ? this.$refs.tests_done.ej2Instances.value : null);
      formData.append("calls", this.selected.calls);

      axios
        .post("/api/ticket", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          console.log(res.data)
          // console.log(res.data)
          if (res.data.success) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.success,
            };

            setTimeout(() => {
              // RESETAR VARIALBES A VACIO O NULL
              this.success = {
                status: false,
                msg: "",
              };
              this.selected = {
                customer_id: "",
                user_id: "",
                department_id: "",
                frame_id: "",
                plate: "",
                brand_id: "",
                model_id: "",
                other_brand_model: "",
                engine_type: "",
                ask_for: "",
                description: "",
                tests_done: "",
                calls: [],
              };
              document.querySelector("input[type='file']").value = "";
              this.$refs.customersSelect.clearSelection();
              this.$refs.brandsSelect.clearSelection();
              this.$refs.modelsSelect.clearSelection();
              this.$refs.description.ej2Instances.value = null;
              this.$refs.tests_done.ej2Instances.value = null;
              this.error = {
                status: false,
                errors: [],
              };
              // REDIRIGIR A Incidencias
              this.$router.push("/ticket");
            }, 2000);
          } else if (res.data.error) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.error = {
              status: true,
              errors: res.data.error,
            };
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    remove_errors() {
      this.error = {
        status: false,
        errors: [],
      };
    },
    selectedCalls(event) {
      this.selected.calls = event;
    },
  }, // END METHODS
};
</script>
<style lang="css">
@import "../../../../node_modules/@syncfusion/ej2-base/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-inputs/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-lists/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-popups/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-buttons/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-navigations/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-splitbuttons/styles/material.css";
@import "../../../../node_modules/@syncfusion/ej2-vue-richtexteditor/styles/material.css";
</style>
<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
</style>