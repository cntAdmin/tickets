<template>
    <div class="mx-3">
        <div class="row justify-content-center">
            <card-counter title="Total" color="primary" :count="blogs.data ? blogs.total : 0" icon="clipboard-list" size="3"/>
            <card-counter title="Publicados" color="secondary" :count="published" icon="clipboard-list" size="3"/>
            <card-counter title="No Publicados" color="secondary" :count="unpublished" icon="clipboard-list" size="3"/>
        </div>

        <div class="d-flex justify-content-center my-3">
            <router-link class="btn btn-secondary btn-sm text-uppercase btn-block" :to="{name: 'blog.create'}">
                Crear Nueva Entrada
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            blogs: [],
            published: 0,
            unpublished: 0,
        }
    },
    methods: {
        getBlogs(data){
            axios.get('/blogs', { params: {
                    subject: data ? data.subject : null
                }
            }).then( res => {
                console.log(res.data);
            }).catch( err => {
                console.log(err);
            })
        }
    }
}
</script>