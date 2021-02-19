<template>
      <div :class="'mt-3 row justify-content-' + align('comment') ">
        <div class="col-10">
            <h4 :class="'title text-' + align('text')">{{ comment.user.name ? comment.user.name : comment.user.username }}</h4>
            <p :class="'h6 text-' + align('text')">{{ comment.created_at | moment("from", "now") }}</p>
            <div class="card mt-1 shadow">
                <div class="card-body"> 
                    <p v-html="comment.description"></p>
                </div> 
                <button class="btn btn-link text-danger ml-auto" title="Eliminar Comentario" @click="openDeleteModal(comment)"
                    v-show="comment.user.id === user.id">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </div>
        </div>
        <div class="col-auto mt-3" v-for="attachment in comment.attachments" :key="attachment.id">
            <a :href="`/api/download/comment/${comment.id}/file/${attachment.id}`"
                class="btn btn-sm btn-success shadow font-weight-bold">
                {{ attachment.name ? attachment.name : attachment.path }}
            </a>
        </div>
        <delete-modal v-show="showDeleteModal" title="Comentario" :data="comment" @close="showDeleteModal = false"
            @getDeleted="deleteComment"></delete-modal>
    </div>
</template>

<script>
export default {
    props: [
        'comment', 'user'
    ],
    data() {
        return {
            user_role: '',
            showDeleteModal: false
        }
    },
    mounted() {
        this.get_user_role();
    },
    methods: {
        openDeleteModal(){
            this.showDeleteModal = true;
        },
        deleteComment() {
            this.showDeleteModal = false;
            axios.delete('/api/ticket/' + this.comment.ticket_id + '/comment/' + this.comment.id)
                .then( res => {
                    this.$emit('succeeded', res.data.msg)
                }).catch( err => {
                    console.log( err )
                });
        },
        get_user_role() {
            axios.get('/api/get_user_role/' + this.comment.user_id)
                .then(res => {
                    this.user_role = res.data.user_role;
                }).catch( err => {
                    console.log(err);
                });
        },
        align(str) {
            switch (str) {
                case 'comment':
                    if(this.user_role === "customer" || this.user_role === 'contact'){
                        return 'end';
                    }
                    return 'start';
                    break;
                case 'text':
                    if(this.user_role === "customer" || this.user_role === 'contact'){
                        return 'right';
                    }
                    return 'left';
                    break;
            
                default:
                    break;
            }
        },
        downloadImage(attachment_id) {
            axios('/media/' + attachment_id)
            .then(res => {
                console.log('res.data', res.data);
            }).catch(err => {
                console.log('err', err);
            });
        }
    },
    filters: {
    }

}
</script>

<style>

</style>