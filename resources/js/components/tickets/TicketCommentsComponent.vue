<template>
    <div class="mx-5 mb-4">
        <div v-for="(comment, idx) in comments" :key="idx">
            <ticket-comment :comment="comment" :key="comment.id" :user="user"></ticket-comment>
        </div>
    </div>
</template>

<script>
export default {
    props: ['comments', 'user'],
}
</script>