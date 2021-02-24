<template>
  <div>from blog view</div>
</template>

<script>
export default {
    data() {
        return {
            posts: []
        }
    },
    activated() {
        this.getPosts();
    },
    methods: {
         getPosts() {
             axios.get('/api/post')
                .then( res => {
                    console.log(res.data.posts)
                    this.posts = res.data.posts;
                })
        },
        getFeatured() {
             axios.get('/api/featured_post')
        },
    },
}
</script>