<template>
    <div :class="'mt-3 d-flex justify-content-' + align('comment') ">
        <div class="col-10">
            <h4 :class="'title text-' + align('text')">{{ username }}</h4>
            <p :class="'h6 text-' + align('text')">{{ timestamp }}</small>
            <div class="card mt-1 shadow">
                <div class="card-body"> 
                    <p v-html="comment.description"></p>
                </div>
            </div>
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
            console.log(this.user_role)
        }
    }
}
</script>