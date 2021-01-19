<template>
    <div :class="'mt-3 row justify-content-' + align('comment') ">
        <div class="col-10">
            <h4 :class="'title text-' + align('text')">{{ username }}</h4>
            <p :class="'h6 text-' + align('text')">{{ timestamp }}</p>
            <div class="card mt-1 shadow">
                <div class="card-body"> 
                    <p v-html="comment.description"></p>
                </div>
            </div>
        </div>
        <div class="col-auto mt-2" v-for="attachment in comment.attachments" :key="attachment.id">
            <a :href="'/media/' + attachment.id" :alt="attachment.name" class="btn btn-sm btn-success" target="_self">
                Descargar - {{ attachment.name }}
            </a>
        </div>
    </div>

</template>

<script>
export default {
    props: [
        'comment', 'user_role' ,'username', 'timestamp'
    ],
    mounted() {

    },
    methods: {
        align(str) {
            switch (str) {
                case 'comment':
                    if(this.user_role[0] === "customer" || this.user_role[0] === 'contact'){
                        return 'end';
                    }
                    return 'start';
                    break;
                case 'text':
                    if(this.user_role[0] === "customer" || this.user_role[0] === 'contact'){
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
    }
}
</script>