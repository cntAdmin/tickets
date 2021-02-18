<template>
  <div class="w-100">
    <div class="card shadow">
      <div class="card-body h-100">
        <div
          v-show="success.status === true"
          class="alert alert-success alert-dismissible fade show text-center"
          role="alert"
        >
          {{ success.msg }}

          <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div v-show="error.status">
          <form-errors :errors="error.errors" @close="closeAll"></form-errors>
        </div>
        <form @submit.prevent="handleSubmit">
          <div class="form-inline">
            <div class="form-group col-12 col-md-6">
              <label class="sr-only">Título</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">Título</div>
                </div>
                <input
                  type="text"
                  class="form-control"
                  v-model="post.title"
                  autofocus
                />
              </div>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="sr-only">Título</label>
              <div class="input-group w-100">
                <div class="input-group">
                  <span class="mr-3 mt-1">Añadir a Destacados</span>
                  <label class="switch">
                    <input type="checkbox" v-model="post.featured" />
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group col-12 mt-2">
              <label class="sr-only">Descripción</label>
              <div class="input-group-text text-uppercase">Descripción</div>
              <div class="input-group w-100">
                <ejs-richtexteditor
                  ref="description"
                  :quickToolbarSettings="quickToolbarSettings"
                  :height="800"
                  :toolbarSettings="toolbarSettings"
                  :enableResize="true"
                >
                </ejs-richtexteditor>
              </div>
            </div>
            <div class="form-group mt-3 w-100">
              <label class="sr-only" for="dateFrom">Adjuntar Fichero(s)</label>
              <div class="input-group w-100">
                <div class="input-group-prepend">
                  <div class="input-group-text text-uppercase">
                    Ficheros adjuntos
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
              type="submit"
              class="btn btn-sm btn-success btn-block mx-3 text-uppercase font-weight-bold"
            >
              Guardar Post
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
  Resize,
} from "@syncfusion/ej2-vue-richtexteditor";

export default {
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar, Resize],
  },
  data() {
    return {
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
      enableResize: true,
      files: [],
      post: {},
      selected: {
        title: "",
        featured: false,
      },
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
    };
  },
  beforeMount() {
    axios.get(`/api/post/${this.$route.params.post}`).then((res) => {
      if (res.data.success) {
        this.post = res.data.post;
        this.$refs.description.ej2Instances.value = this.post.description;
      }
    });
  },
  methods: {
    closeAll() {
      this.success.status = false;
      this.error.status = false;
    },
    uploadFile(e) {
      this.files = e.target.files;
    },
    handleSubmit() {
      this.closeAll();
      const formData = new FormData();
      if (this.files) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
      }
      formData.append("title", this.post.title);
      formData.append("description", this.$refs.description.ej2Instances.value);
      formData.append("featured", this.post.featured);

      axios
        .post(`/api/edit_post/${this.post.id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          $("html, body").animate({ scrollTop: 0 }, "slow");
          if (res.data.success) {
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.$router.push("/post");
            }, 2000);
          } else if (res.data.error) {
            this.error = {
              status: true,
              errors: res.data.errors,
            };
          }
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>
<style scoped>
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