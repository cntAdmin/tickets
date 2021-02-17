<template>
    <nav class="navbar navbar-light bg-white shadow mt-1 h-100 col-2 flex-column overflow-auto position-fixed">
        <div class="container">
            <button class="btn btn-block btn-link border-bottom d-block d-md-none" type="button" data-toggle="collapse" data-target="#sidebar_navbar"
                aria-controls="sidebar_navbar" aria-expanded="true" aria-label="Toggle navigation" title="Contraer/Expandir Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="accordion navbar-collapse mt-2 d-none d-md-block" id="sidebar_navbar">
                <ul class="navbar-nav">
                    <li class="nav-item w-100">
                        <div class="d-flex align-items-center shadow-sm w-100" data-toggle="collapse"
                            data-target="#tickets_sidebar" aria-expanded="true" aria-controls="tickets_sidebar">
                            <div class="mr-auto">
                                <button class="btn btn-toolbar text-uppercase font-weight-bold w-100">
                                    Tickets
                                </button>
                            </div>
                            <div class="ml-auto">
                                <span class="navbar-toggler-icon" type="button" data-toggle="collapse"
                                    data-target="#tickets_sidebar" aria-expanded="true" aria-controls="tickets_sidebar">
                                </span>
                            </div>
                        </div>
                        <div class="collapse" id="tickets_sidebar">
                            <router-link class="btn btn-toolbar btn-block mt-2" :to="{ name: 'ticket.index' }">
                                <span class="ml-2">Todos los Tickets</span>
                            </router-link>
                            <router-link class="btn btn-toolbar btn-block mt-2" :to="{ name: 'ticket.index', query: {'status':1} }">
                                <span class="ml-2">Tickets Abiertos</span>
                            </router-link>
                            <router-link class="btn btn-toolbar btn-block mt-2" :to="{ name: 'ticket.index', query: {'status':2} }">
                                <span class="ml-2">Tickets Cerrados</span>
                            </router-link>
                            <router-link class="btn btn-toolbar btn-block mt-2" :to="{ name: 'ticket.index', query: {'status':3} }">
                                <span class="ml-2">Tickets Resueltos</span>
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item mt-2">
                        <div class="shadow-sm collapsed" data-toggle="collapse" aria-expanded="false">
                            <router-link id="departments" class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'department.index'}">
                                Departamentos
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <router-link class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'customer.index'}">
                                Clientes
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <router-link class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'users.index'}">
                                Usuarios / Contactos
                            </router-link>
                        </div>
                    </li>
                    <!-- <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <router-link class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'faqs.index'}">
                                FAQ's
                            </router-link>
                        </div>
                    </li> -->
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <router-link class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'post.index'}">
                                Blog
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <router-link class="btn btn-toolbar text-uppercase font-weight-bold" :to="{ name: 'file_manager.index'}">
                                Media
                            </router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {

}
</script>

<style>

</style>