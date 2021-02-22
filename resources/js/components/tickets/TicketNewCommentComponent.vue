<template>
  <div class="row justify-content-center my-3 mx-4">
    <div class="col-6 mt-2 text-center">
      <div
        v-show="success.value === true"
        class="alert alert-success alert-dismissible fade show"
        role="alert"
      >
        {{ success.message }}

        <button
          type="button"
          class="close"
          data-dismiss="alert"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
    <div :class="'col-10 ' + align()">
      <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
        <div class="form-inline">
          <div class="form-group shadow">
            <label class="sr-only" for="dateFrom">Comentario</label>
            <div class="input-group w-100">
              <div class="input-group-prepend">
                <div class="input-group-text text-uppercase">Comentario</div>
              </div>
            </div>
            <ejs-richtexteditor
              ref="comment"
              :quickToolbarSettings="quickToolbarSettings"
              :height="400"
              :toolbarSettings="toolbarSettings"
            >
            </ejs-richtexteditor>
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
        <input
          class="btn btn-success btn-sm mt-3 shadow"
          type="submit"
          value="Enviar Comentario"
        />
      </form>
    </div>
  </div>
</template>

<script>
import {
  RichTextEditorPlugin,
  Toolbar,
  Image,
  Link,
  HtmlEditor,
  QuickToolbar,
} from "@syncfusion/ej2-vue-richtexteditor";

Vue.use(RichTextEditorPlugin);

export default {
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },
  deactivated() {
    // GLOBAL FUNCTION IN APP.JS
    this.resetFields();
  },
  props: ["user_role", "ticket_id"],
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
      files: null,
      success: {
        value: false,
        message: "",
      },
      error: false,
    };
  },
  methods: {
    uploadFile(e) {
      this.files = e.target.files;
    },

    handleSubmit() {
      const formData = new FormData();
      if (this.files) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
      }
      formData.append("comment", this.$refs.comment.ej2Instances.value);

      axios
        .post("/api/ticket/" + this.ticket_id + "/comment", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.data.success) {
            this.success.value = true;
            this.success.message = res.data.msg;
            this.$emit("load");
            this.$refs.comment.ej2Instances.value = "";
            (this.files = null),
              setTimeout(() => {
                this.success.value = false;
                this.success.message = "";
              }, 2000);
          } else if (res.data.error) {
            this.success = false;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    align() {
      if (this.user_role === "undefined" || this.user_role === "contact") {
        return "ml-auto";
      }
      return "mr-auto";
    },
  },
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