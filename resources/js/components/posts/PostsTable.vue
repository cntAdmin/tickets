<template>
  <div class="w-100">
    <div class="card shadow mt-3" v-if="posts.total > 0">
      <div class="card-body">
        <table class="table table-hover table-striped shadow text-left">
          <thead class="thead-dark">
            <tr class="text-center text-uppercase">
              <th scope="col">#</th>
              <th scope="col">Creado Por</th>
              <th scope="col">Título</th>
              <th scope="col">Fecha</th>
              <th scope="col">Publicado</th>
              <th scope="col">Destacado</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in posts.data" :key="post.id">
              <th scope="row">{{ post.id }}</th>
              <td>{{ post.created_by.name }}</td>
              <td>{{ post.title }}</td>
              <td class="text-center">
                {{ post.created_at | moment("DD-MM-YYYY") }}
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <div class="input-group">
                    <label class="switch">
                      <input
                        type="checkbox"
                        v-model="post.published"
                        name="published"
                        @click="handleCheckbox($event, post.id)"
                      />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <div class="input-group">
                    <label class="switch">
                      <input
                        type="checkbox"
                        v-model="post.featured"
                        name="featured"
                        @click="handleCheckbox($event, post.id)"
                      />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <router-link
                    class="btn btn-sm btn-success"
                    :to="{ name: 'post.show', params: { post: post.id } }"
                  >
                    <i class="fas fa-eye"></i>
                  </router-link>
                  <router-link
                    class="btn btn-sm btn-info text-white ml-3"
                    :to="{ name: 'post.edit', params: { post: post.id } }"
                  >
                    <i class="fas fa-edit"></i>
                  </router-link>
                  <button
                    type="button"
                    class="btn btn-sm btn-danger ml-3"
                    @click="openDeleteModal(post)"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        :data="posts"
        @pagination-change-page="emit_pagination"
        :limit="3"
        size="small"
        align="center"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
    </div>
    <div v-else class="mt-3 shadow">
      <div class="alert alert-warning text-center">Haga una nueva búsqueda</div>
    </div>
    <delete-modal
      v-show="showDelete"
      :data="post"
      title="Post"
      @getDeleted="getDeleted"
      @close="showDelete = false"
    ></delete-modal>
  </div>
</template>

<script>
export default {
  props: ["posts", "searched"],
  data() {
    return {
      post: {},
      showDelete: false,
    };
  },
  methods: {
    getDeleted() {
      axios
        .delete(`/api/post/${this.post.id}`)
        .then((res) => {
          // console.log(res.data);
        })
        .catch((err) => console.log(err));
    },
    openDeleteModal(post) {
      this.showDelete = true;
      this.post = post;
    },
    handleCheckbox(e, post_id) {
      axios
        .get("/api/toggle_post/" + post_id, {
          params: { toggle: e.target.name },
        })
        .then((res) => this.$emit("getCount"));
    },
    emit_pagination(page) {
      this.searched.page = page;
      this.$emit("page", this.searched);
    },
  },
};
</script>

<style>
switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>