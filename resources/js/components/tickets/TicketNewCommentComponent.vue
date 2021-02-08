<template>
    <div class="row justify-content-center mt-3">
        <div class="col-6 mt-2 text-center">
            <div v-show="success.value === true" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ success.message }}

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        </div>
        <div :class="'col-10 ' + align()">
            <form @submit="handleSubmit">
                <div class="form-inline">
                    <div class="form-group">
                        <label class="sr-only" for="dateFrom">Comentario</label>
                        <div class="input-group w-100">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-uppercase">Comentario</div>
                            </div>
                        </div>
                        <ejs-richtexteditor ref="comment" :quickToolbarSettings="quickToolbarSettings" :height="400"
                            :toolbarSettings="toolbarSettings">
                        </ejs-richtexteditor>
                    </div>
                </div>
                <input class="btn btn-success btn-sm mt-2" type="submit" value="Enviar Comentario">
            </form>
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
        'user_role', 'ticket_id'
    ],
    data() {
        return {
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
            success: {
                value: false,
                message: ''
                },
            error: false
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();

            axios.post('/ticket/' + this.ticket_id + '/comment', {
                comment: this.$refs.comment.ej2Instances.value,
            }).then(res => {
                if(res.data.success) {
                    this.success.value = true;
                    this.success.message = res.data.success;

                    setTimeout(() => {
                        window.location.href = '/ticket/' + this.ticket_id
                    }, 1500);

                }else if(res.data.error) {
                    this.success = false;
                }
            }).catch(err => {
                console.log(err);
            })
        },
        align() {
            if(this.user_role[0] === "undefined" || this.user_role[0] === 'contact'){
                return 'ml-auto';
            }
            return 'mr-auto';
        }
    }
}
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