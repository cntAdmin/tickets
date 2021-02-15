<template>
    <div class="mx-5 mb-4">
        <div v-for="(comment, idx) in comments" :key="idx">
            <ticket-comment :comment="comment" :key="comment.id" :user="user" @succeeded="succeeded"></ticket-comment>
        </div>
    </div>
</template>

<script>
export default {
    props: ['comments', 'user'],
    methods: {
        succeeded(data) {
            console.log('datacomments', data);
            this.$emit('succeeded', data);
        }
    }
}
</script>