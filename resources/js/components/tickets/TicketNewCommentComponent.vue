<template>
  <div class="row justify-content-center my-3 mx-2 mx-xl-4">
    <div class="col-12 col-xl-6 mt-2 text-center" v-if="success.status">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
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
    </div>
    <div class="col-12 col-xl-6 mt-2 text-center" v-if="error.status">
        <form-errors
          :errors="error.errors"
          @close="error.status = false"
        ></form-errors>
    </div>

    <transition name="fade" v-if="spinner" mode="out-in">
      <div class="mt-5 pt-5 vh-100">
        <spinner></spinner>
      </div>
    </transition>

    <transition name="fade" v-else mode="out-in">
      <div :class="'col-12 col-xl-10 ' + align()">
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
          <div class="form-inline">
            <div class="form-group w-100 shadow">
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
                    <span class="d-none d-xl-inline-block">Ficheros </span>
                    adjuntos
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
    </transition>
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
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
      spinner: false,
    };
  },
  methods: {
    uploadFile(e) {
      this.files = e.target.files;
    },

    handleSubmit() {
      this.spinner = true;
      if (this.$refs.comment.ej2Instances.value === null) {
        this.error.status = true;
        this.error.errors = { comment: [] };

        this.error.errors["comment"].push(
          "Es obligatorio escribir un comentario."
        );
        this.spinner = false;
        return;
      }

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
          console.log(res.data)
          this.spinner = false;

          if (res.data.success) {
            this.success.status = true;
            this.success.msg = res.data.msg;
            this.$emit("load");
            this.files = null;

            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$router.push("/ticket");
            }, 2500);
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