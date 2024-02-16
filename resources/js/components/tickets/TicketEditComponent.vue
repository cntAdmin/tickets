<template>
  <div class="w-100">
    <div class="card mt-4">
      <div class="card-header">
        <div class="d-flex justify-content-around">
          <div class="mr-auto">
            <h4 class="title text-uppercase" v-if="ticket">Incidencia ({{ ticket.custom_id }})</h4>
          </div>
          <div class="ml-auto" v-if="ticket">
            <div class="form-inline">
              <div class="input-group">
                <span class="mr-3 mt-1">Añadir a FAQ's</span>
                <label class="switch">
                  <input type="checkbox" v-model="ticket.knowledge_base" />
                  <span class="slider round"></span>
                </label>
              </div>
              <button form="edit_ticket_form" type="submit" class="btn btn-sm btn-success mx-3">
                Guardar Incidencia
              </button>

              <router-link class="btn btn-sm btn-secondary" :to="{ name: 'ticket.show', params: { ticketID: ticketID } }">
                Volver
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div v-if="success.status" class="alert alert-success alert-dismissible fade show text-center">
          {{ success.msg }}
        </div>
        <div v-if="error.status" class="alert alert-danger alert-dismissible fade show">
          <form-errors :errors="error.errors" @close="error.status = false"></form-errors>
        </div>
        <form id="edit_ticket_form" @submit.prevent="handleSubmit" method="POST">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6 col-lg-4">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">
                    Cliente
                  </div>
                </div>
                <input class="form-control" type="text" v-model="ticket.customer.comercial_name" disabled/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Contacto</div>
                </div>
                <input class="form-control" type="text" v-model="ticket.user.name" disabled/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Departamentos</div>
                </div>
                <select class="form-control" v-model="ticket.department_id">
                  <option v-for="dpt in departments" :key="dpt.id" :value="dpt.id">
                    {{ dpt.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Nº Bastidor</div>
                </div>
                <input class="form-control" type="text" v-model="ticket.frame_id"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Matrícula</div>
                </div>
                <input class="form-control" type="text" v-model="ticket.plate"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Tipo de Motor</div>
                </div>
                <input class="form-control" type="text" v-model="ticket.engine_type"/>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Marca</div>
                </div>
                <vue-select
                  class="col-8 px-0 mx-0"
                  transition="vs__fade"
                  :options="brands"
                  label="name"
                  itemid="id"
                  @input="setBrand"
                  v-model="ticket.brand.name"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Modelo</div>
                </div>
                <vue-select
                  class="col-8 px-0 mx-0"
                  transition="vs__fade"
                  :options="models"
                  label="name"
                  itemid="id"
                  @input="setModel"
                  v-model="ticket.car_model.name"
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
                <input class="form-control" type="text" v-model="ticket.other_brand_model" v-if="!ticket.car_model_id"/>
                <input v-else class="form-control" type="text" disabled />
              </div>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <!-- <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Solicito</div>
                </div>
                <input class="form-control" type="text" v-model="ticket.ask_for"/>
              </div> -->
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Solicito</div>
                </div>
                <vue-select
                  class="col-8 px-0 mx-0"
                  transition="vs__fade"
                  :options="ask_for"
                  label="name"
                  itemid="id"
                  @input="setAskFor"
                  v-model="ticket.ask_for.name"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
            <div class="form-group col-12 col-md-6 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Asunto</div>
                </div>
                <input class='form-control' type="text" v-model="ticket.subject"/>
              </div>
            </div>
            <!-- <calls-modal @selectedCalls="selectedCalls($event)"></calls-modal> -->
            <div class="form-group col-12 col-md-6 col-lg-4 order-0 order-lg-0 mt-2">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase py-1">Asignar a...</div>
                </div>
                <vue-select
                  class="col-8 px-0 mx-0"
                  transition="vs__fade"
                  :options="agents"
                  label="name"
                  itemid="id"
                  @input="setAgent"
                  :value="ticket.assigned_to.name"
                >
                  <div slot="no-options">No hay opciones con esta busqueda</div>
                  <template slot="option" slot-scope="option">
                    {{ option.name }}
                  </template>
                </vue-select>
              </div>
            </div>
          </div>
          <div class="form-inline mt-2">
            <div class="form-group col-12">
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
          <!-- <div class="form-inline mt-2">
            <div class="form-group col-12">
              <label class="sr-only" for="dateFrom">Pruebas Realizadas</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
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
          </div> -->
          <div class="form-inline mx-3">
            <div class="form-group mt-2 w-100">
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    <span class="d-none d-xl-block mx-1">Ficheros</span>adjuntos <sub>(max. 25MB)</sub>
                  </div>
                </div>
                <input class="form-control" type="file" @change="uploadFile" multiple/>
              </div>
            </div>
          </div>
          <div class="form-inline mt-4">
            <button form="edit_ticket_form" type="submit" class="btn btn-sm btn-success btn-block mx-3">
              Guardar Incidencia
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
    // GLOBAL FUNCTION IN APP.JS
    this.resetFields();
  },

  props: ["ticketID"],
  data() {
    return {
      users: [],
      customers: [],
      brands: [],
      models: [],
      agents: [],
      ask_for: [],
      departments: {},
      // calls: {},
      ticket: {
        user: {},
        customer: {},
        brand: {},
        car_model: {},
        assigned_to: {},
        ask_for: {},
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
    };
  },
  activated() {
    this.get_all_departments();
    this.get_all_customers();
    this.get_ticket(parseInt(this.ticketID));
    this.get_all_brands();
    this.get_all_agents();
    this.get_all_ask_for();
  },
  methods: {
    uploadFile(e) {
      this.files = e.target.files;
    },
    setCustomer(value) {
      this.ticket.customer.id = value ? value.id : null;
      this.get_all_users();
    },
    setModel(value) {
      this.ticket.car_model_id = value ? value.id : null;
    },
    setAgent(value) {
      this.ticket.assigned_to = value ? value.id : null;
    },
    setAskFor(value) {
      this.ticket.ask_for_id = value ? value.id : null;
    },
    setBrand(value) {
      this.ticket.brand_id = value ? value.id : null;
      if (value) {
        axios.get("/api/brand/" + this.ticket.brand_id + "/model").then((res) => {
          this.models = res.data.models;
        }).catch((err) => {
          console.log(err);
        });
      }
    },
    get_all_brands() {
      axios.get("/api/get_all_brands").then((res) => {
        this.brands = res.data.brands;
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
        this.ticket.department_id = this.departments[0].id;
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
      if (this.ticket.customer.id !== "") {
        axios.get("/api/get_all_users", {params: {
            customer_id: this.ticket.customer.id,
          },
        }).then((res) => {
          this.users = res.data.users;
        }).catch((err) => {
          console.log(err);
        });
      }
    },
    get_ticket(ticketID) {
      axios.get("/api/ticket/" + ticketID).then((res) => {
        this.ticket = res.data.ticket;
        this.users[0] = res.data.ticket.user;
        this.$refs.description.ej2Instances.value = this.ticket.description;
        // this.$refs.tests_done.ej2Instances.value = this.ticket.tests_done;
        if (this.ticket.brand === null) {
          this.ticket.brand = {
            id: 0,
            name: "",
          };
        }
        if (this.ticket.car_model === null) {
          this.ticket.car_model = {
            id: 0,
            name: "",
          };
        }
        if (this.ticket.assigned_to === null) {
          this.ticket.assigned_to = {
            id: 0,
            name: "",
          };
        }
        if (this.ticket.ask_for === null) {
          this.ticket.ask_for = {
            id: 0,
            name: "",
          };
        }
      })
      .catch((err) => {
        console.log(err);
      });
    },
    handleSubmit() {
      if (this.ticket.frame_id === null && this.ticket.plate === null) {
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

      if (this.ticket.frame_id !== null) {
        formData.append("frame_id", this.ticket.frame_id);
      }
      if (this.ticket.plate !== null) {
        formData.append("plate", this.ticket.plate);
      }
      if (this.ticket.brand_id !== null) {
        formData.append("brand_id", this.ticket.brand_id);
      }
      if (this.ticket.car_model_id !== null) {
        formData.append("model_id", this.ticket.car_model_id);
      }
      // if (this.$refs.tests_done.ej2Instances.value !== null) {
      //   formData.append("tests_done", this.$refs.tests_done.ej2Instances.value);
      // }
      if (this.ticket.other_brand_model !== null) {
        formData.append("other_brand_model", this.ticket.other_brand_model);
      }
      if (this.ticket.engine_type !== null) {
        formData.append("engine_type", this.ticket.engine_type);
      }
      if (this.ticket.assigned_to !== null) {
        formData.append("assigned_to", this.ticket.assigned_to.id);
      }
      if (this.ticket.ask_for_id !== null) {
        formData.append("ask_for_id", this.ticket.ask_for_id);
      }
      // if (this.ticket.calls !== null) {
      //   formData.append("calls", this.ticket.calls);
      // }

      // console.log("   --> handleSubmit() ");
      // console.log("   --> this.ticket.customer_id: ",this.ticket.customer_id);
      // console.log("   --> this.ticket.user_id: ",this.ticket.user_id);
      // console.log("   --> this.ticket.department_id: ",this.ticket.department_id);
      // console.log("   --> this.ticket.frame_id: ",this.ticket.frame_id);
      // console.log("   --> this.ticket.plate: ",this.ticket.plate);
      // console.log("   --> this.ticket.brand_id: ",this.ticket.brand_id);
      // console.log("   --> this.ticket.car_model_id: ",this.ticket.car_model_id);
      // console.log("   --> this.ticket.other_brand_model: ",this.ticket.other_brand_model);
      // console.log("   --> this.ticket.ask_for: ",this.ticket.ask_for);
      // console.log("   --> this.ticket.subject: ",this.ticket.subject);
      // console.log("   --> this.ticket.assigned_to: ",this.ticket.assigned_to.id);
      // console.log("   --> this.ticket.ask_for_id: ",this.ticket.ask_for_id);
      // console.log("   --> this.$refs.description.ej2Instances.value: ",this.$refs.description.ej2Instances.value);
      // console.log("   --> this.$refs.tests_done.ej2Instances.value: ",this.$refs.tests_done.ej2Instances.value);
      // console.log("   --> this.files: ",this.files);
      // return;
      formData.append("customer_id", this.ticket.customer_id);
      formData.append("user_id", this.ticket.user_id);
      formData.append("department_id", this.ticket.department_id);
      // formData.append("engine_type", this.ticket.engine_type);
      formData.append("ask_for", this.ticket.ask_for);
      formData.append("subject", this.ticket.subject);
      formData.append("description", this.$refs.description.ej2Instances.value);
      // formData.append("calls", this.ticket.calls);

      axios.post(`/api/ticket/${this.ticket.id}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }).then((res) => {
        if (res.data.success) {
          $("html, body").animate({ scrollTop: 0 }, "slow");

          this.success = {
            status: true,
            msg: res.data.msg
          };

          setTimeout(() => {
            this.$router.push(`/incidencia/${this.ticket.id}`);
          }, 2000);
        }
        else if (res.data.error) {
          this.error = {
            status: true,
            errors: res.data.errors,
          };
        }
      }).catch((err) => {
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
    //   this.ticket.calls = event;
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

switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>