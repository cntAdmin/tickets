<template>
      <div :class="'mt-3 row justify-content-' + align('comment') ">
        <div class="col-10">
            <h4 :class="'title text-' + align('text')">{{ comment.user.name }}</h4>
            <p :class="'h6 text-' + align('text')">{{ comment.created_at | moment("from", "now") }}</p>
            <div class="card mt-1 shadow">
                <div class="card-body"> 
                    <p v-html="comment.description"></p>
                </div>
            </div>
        </div>
        <div class="col-auto mt-3" v-for="attachment in comment.attachments" :key="attachment.id">
            <a :href="'/media/' + attachment.id" :alt="attachment.name" class="btn btn-sm btn-success shadow" target="_self">
                Descargar - {{ attachment.name }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'comment'
    ],
    data() {
        return {
            user_role: '',
        }
    },
    mounted() {
        this.get_user_role();
    },
    methods: {
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