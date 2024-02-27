<template>
  <div class="w-100">
    <div v-if="success.status" class="alert alert-success alert-dismissible fade show text-center">
      {{ success.msg }}
    </div>
    <div v-if="error.status" class="alert alert-danger alert-dismissible fade show text-center">
      <form-errors :errors="error.errors" @close="error.status = false"></form-errors>
    </div>
    <div class="card mt-4">
      <div class="card-body">
        <form id="create_ticket_form" @submit.prevent="handleSubmit" method="POST">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Cliente</div>
                </div>
                <vue-select
                  v-if="!is_admin"
                  class="col-8 px-0 w-100"
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
                  :class="'col-8 px-0 w-100'"
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
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Contacto</div>
                </div>
                <select v-if="!is_admin" class="form-control" :value="user.id" disabled>
                  <option :value="user.id">{{ user.name }}</option>
                </select>
                <select v-else :class="' form-control'" v-model="selected.user_id">
                  <option value="" disabled>-- SELECCIONE UN USUARIO --</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Departamentos</div>
                </div>
                <select :class="' form-control'" v-model="selected.department_id">
                  <option v-for="dpt in departments" :key="dpt.id" :value="dpt.id">
                    {{ dpt.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nº Bastidor</div>
                </div>
                <input :class="' form-control'" type="text" v-model="selected.frame_id"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-1">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Matrícula</div>
                </div>
                <input :class="' form-control'" type="text" v-model="selected.plate"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Tipo de Motor</div>
                </div>
                <input :class="' form-control'" type="text" v-model="selected.engine_type"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Marca</div>
                </div>
                <vue-select
                  :class="'col-8 px-0 mx-0'"
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
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Modelo</div>
                </div>
                <vue-select
                  :options="models"
                  ref="modelsSelect"
                  class="col-8 px-0 mx-0"
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
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Otro Marca/Modelo</div>
                </div>
                <input :class="'form-control'" type="text" v-model="selected.other_brand_model" v-if="!selected.model_id"/>
                <input v-else class="form-control" type="text" disabled />
              </div>
            </div>
          </div>
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Asunto</div>
                </div>
                <input class='form-control' type="text" v-model="selected.subject"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Solicito</div>
                </div>
                <vue-select
                  class="col-8 px-0"
                  transition="vs__fade"
                  ref="solicitoSelect"
                  :options="ask_for"
                  label="name"
                  itemid="id"
                  @input="setAskFor"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Asignado a...</div>
                </div>
                <vue-select
                  class="col-8 px-0"
                  transition="vs__fade"
                  ref="agentSelect"
                  :options="agents"
                  label="name"
                  itemid="id"
                  @input="setAgent"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <!-- <calls-modal @selectedCalls="selectedCalls($event)"></calls-modal> -->
          </div>
          <div class="form-inline">
            <div class="form-group col-12 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Descripcion</div>
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
          <div class="form-inline mx-3">
            <div class="form-group mt-2 w-100">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    <span class="d-none d-xl-block mx-1">Ficheros</span> adjuntos <sub>(max. 25MB)</sub>
                  </div>
                </div>
                <input class="form-control" type="file" @change="uploadFile" multiple/>
              </div>
            </div>
          </div>
          <div class="form-inline mt-4">
            <button form="create_ticket_form" type="submit" class="btn btn-success btn-block mx-3">
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
      agents: [],
      ask_for: [],
      departments: {},
      // calls: {},
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
        subject: "",
        assigned_to_id: "",
        ask_for_id: "",
        description: "",
        // calls: [],
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
      ],
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
    this.get_all_agents();
    this.get_all_ask_for();
    if (this.$route.params.customer_id) {
      axios.get("/api/customer/" + this.$route.params.customer_id).then((res) => {
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
    setAgent(value) {
      this.selected.assigned_to_id = value ? value.id : null;
    },
    setAskFor(value) {
      this.selected.ask_for_id = value ? value.id : null;
    },
    setBrand(value) {
      this.selected.brand_id = value ? value.id : null;
      if (value) {
        axios.get("/api/brand/" + this.selected.brand_id + "/model").then((res) => {
          this.models = res.data.models;
        }).catch((err) => {
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
      axios.get("/api/get_all_brands").then((res) => {
        this.brands = res.data.brands;
      }).catch((err) => {
        console.log(err);
      });
    },
    get_all_agents() {
      axios.get("/api/get_all_agents").then((res) => {
        this.agents = res.data.agents;
      });
    },
    get_all_ask_for() {
      axios.get("/api/get_all_ask_for").then((res) => {
        this.ask_for = res.data.ask_for;
      });
    },
    get_all_departments() {
      axios.get("/api/get_all_departments").then((res) => {
        this.departments = res.data.departments;
        this.selected.department_id = this.departments[0].id;
      }).catch((err) => {
        console.log(err);
      });
    },
    get_all_customers() {
      axios.get("/api/get_all_customers").then((res) => {
        this.customers = res.data.customers;
      }).catch((err) => {
        console.log(err);
      });
    },
    get_all_users() {
      if (this.selected.customer_id !== "" || this.$route.params.customer_id) {
        axios.get("/api/get_all_users", {params: {
          customer_id: this.$route.params.customer_id
            ? this.customer.id
            : this.selected.customer_id,
          },
        }).then((res) => {
          this.users = res.data.users;
        }).catch((err) => {
          console.log(err);
        });
      }
    },
    handleSubmit() {
      if (this.selected.frame_id === "" && this.selected.plate === "") {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        this.error.status = true;
        this.error.errors = { frame_id: [], plate: [] };
        this.error.errors["frame_id"].push("Uno de los campos 'nº bastidor' o 'matrícula' es obligatorio.");
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
      if(this.selected.frame_id !== null) {
        formData.append("frame_id", this.selected.frame_id);
      }
      if(this.selected.plate !== null) {
        formData.append("plate", this.selected.plate);
      }
      if(this.selected.brand_id !== null) {
        formData.append("brand_id", this.selected.brand_id);
      }
      if(this.selected.model_id !== null) {
        formData.append("model_id", this.selected.model_id);
      }
      if(this.selected.other_brand_model !== null) {
        formData.append("other_brand_model", this.selected.other_brand_model);
      }
      if(this.$refs.description.ej2Instances.value !== null) {
        formData.append("description", this.$refs.description.ej2Instances.value);
      }
      else{
        formData.append("description", ' ');
      }

      formData.append("customer_id", this.selected.customer_id);
      formData.append("user_id", this.selected.user_id);
      formData.append("department_id", this.selected.department_id);
      formData.append("engine_type", this.selected.engine_type);
      formData.append("ask_for_id", this.selected.ask_for_id);
      formData.append("subject", this.selected.subject);
      // formData.append("calls", this.selected.calls);
      formData.append("assigned_to_id", this.selected.assigned_to_id);

      // console.log(this.selected);  

      axios.post("/api/ticket", formData, {headers: {
            "Content-Type": "multipart/form-data",
          },
        }).then((res) => {
          // console.log(res.data);

          if (res.data.success) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.msg,
            };

            setTimeout(() => {
              // RESETAR VARIALBES A VACIO O NULL
              // this.disableButton = false;
              this.success = {
                status: false,
                msg: '',
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
                subject: "",
                ask_for_id: "",
                assigned_to_id: "",
                description: "",
                // calls: [],
              };

              document.querySelector("input[type='file']").value = "";
              this.$refs.customersSelect.clearSelection();
              this.$refs.brandsSelect.clearSelection();
              this.$refs.modelsSelect.clearSelection();
              this.$refs.solicitoSelect.clearSelection();
              this.$refs.agentSelect.clearSelection();
              this.$refs.description.ej2Instances.value = '';
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
    // selectedCalls(event) {
    //   this.selected.calls = event;
    // },
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