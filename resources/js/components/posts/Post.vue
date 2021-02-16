<template>
    <div class="mx-3">
        <div class="row justify-content-center">
            <card-counter title="Total" color="primary" :count="posts.data ? posts.total : 0" icon="clipboard-list" size="3"/>
            <card-counter title="Publicados" color="secondary" :count="published" icon="clipboard-list" size="3"/>
            <card-counter title="No Publicados" color="secondary" :count="posts.data ? posts.total - published : 0" icon="clipboard-list" size="3"/>
        </div>

        <div class="d-flex justify-content-center my-3">
            <router-link class="btn btn-secondary btn-sm text-uppercase btn-block" :to="{name: 'post.create'}">
                Crear Nueva Entrada
            </router-link>
        </div>

        <posts-search-form @search="getPosts"></posts-search-form>

        <div class="alert alert-dismissable alert-danger my-3" v-if="deleted.status">
            {{ deleted.msg }}
        </div>
        <transition name="fade" v-else-if="searching" mode="out-in">
            <div class="row justify-content-center mt-5">
                <spinner></spinner>
            </div>
        </transition>

        <transition name="fade" v-else-if="posts.data" mode="out-in">
            <posts-table :posts="posts" @page="getPosts" :searched="searched" @deleted="hasBeenDeleted"></posts-table>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            blogs: [],
            posts: [],
            searched: [],
            published: 0,
            searching: false,
            deleted: {
                status: false,
                msg: '',
            },
            success: {
                status: false,
                msg: '',
            }
        }
    },
    methods: {
        hasBeenDeleted(data) {
            this.deleted = {
                status: true,
                msg: data
            }
        },
        getPosts(data){
            this.searching = true;
            this.getCounters();
            axios.get('/api/post', { params: {
                    page: data ? data.page : 1,
                    text: data ? data.text : null,
                    date_from: data ? data.date_from : null,
                    date_to: data ? data.date_to : null
                }
            }).then( res => {
                this.searching = false;
                this.posts = res.data.posts;
            }).catch( err => {
                console.log(err);
            })
        },
        getCounters() {
            axios.get('/api/get_posts_counters')
                .then( res => {
                    this.published = res.data.published;
                }).catch( err => console.log(err));
        }
    }
}
</script>