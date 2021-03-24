/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import router from './components/router';
import {
    RichTextEditorPlugin
} from "@syncfusion/ej2-vue-richtexteditor";
import vSelect from 'vue-select';
import Vuex from 'vuex';
import FileManager from 'laravel-file-manager';
import VueScreen from 'vue-screen';


//  console.log(router)
require('./bootstrap');

window.Vue = require('vue');
Vue.use(Vuex);
Vue.use(VueScreen, 'bootstrap');

const moment = require('moment')
require('moment/locale/es')

Vue.use(require('vue-moment'), {
    moment
})
Vue.use(RichTextEditorPlugin);

const store = new Vuex.Store();
Vue.use(FileManager, {
    store
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('App', require('./components/AppComponent').default);
Vue.component('admin-sidebar', require('./components/AdminSidebar').default);
Vue.component('sidebar', require('./components/Sidebar').default);
Vue.component('navbar', require('./components/Navbar').default);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('spinner', require('./components/Spinner.vue').default);
Vue.component('card-counter', require('./components/CardCounter').default);
Vue.component('delete-modal', require('./components/DeleteModal').default);
Vue.component('image-modal', require('./components/ImageModal').default);
Vue.component('form-errors', require('./components/FormErrors').default);
Vue.component('exports', require('./components/Exports').default);
Vue.component('navbar-test', require('./components/NavbarTest').default);
Vue.component('mobile-bottom-navbar', require('./components/MobileBottomNavbar').default);

Vue.component('vue-select', vSelect)

// TICKETS
Vue.component('tickets', require('./components/tickets/Tickets').default);
Vue.component('tickets-table', require('./components/tickets/TicketsTable').default);
Vue.component('tickets-search-form', require('./components/tickets/TicketsSearchForm').default);
Vue.component('ticket-view', require('./components/tickets/TicketView').default);
Vue.component('ticket-view-info', require('./components/tickets/TicketViewInfoComponent').default);

// MOBILE
Vue.component('mobile-tickets-cards-table', require('./components/tickets/MobileTicketsCardsTable').default);

// CREATE TICKET
Vue.component('tickets-create', require('./components/tickets/TicketsCreateComponent').default);
Vue.component('calls-modal', require('./components/CallsModalComponent').default);
// VIEW TICKET 
Vue.component('ticket-view-info', require('./components/tickets/TicketViewInfoComponent').default);
Vue.component('ticket-comments', require('./components/tickets/TicketCommentsComponent').default);
Vue.component('ticket-comment', require('./components/tickets/TicketCommentComponent').default);
Vue.component('ticket-new-coment', require('./components/tickets/TicketNewCommentComponent').default);
Vue.component('ticket-view-calls', require('./components/tickets/TicketViewCallsComponent').default);
// MOBILE
Vue.component('ticket-cards-calls', require('./components/tickets/MobileTicketCardsCalls').default);

// DEPARTMENTS
Vue.component('departments', require('./components/departments/Departments').default);
Vue.component('departments-search-form', require('./components/departments/DepartmentsSearchForm').default);
Vue.component('departments-table', require('./components/departments/DepartmentsTable').default);
Vue.component('department-new', require('./components/departments/DepartmentNew').default);
Vue.component('department-edit', require('./components/departments/DepartmentEdit').default);
Vue.component('department-assign-user', require('./components/departments/DepartmentAssignUser').default);
Vue.component('department-assign-form', require('./components/departments/DepartmentAssignForm').default);
Vue.component('department-assign-table', require('./components/departments/DepartmentAssignTable').default);


// CUSTOMERS
Vue.component('customers', require('./components/customers/Customers').default);
Vue.component('customers-search-form', require('./components/customers/CustomersSearchForm').default);
Vue.component('customers-table', require('./components/customers/CustomersTable').default);
Vue.component('customer-new', require('./components/customers/CustomerNew').default);
Vue.component('customer-edit', require('./components/customers/CustomerEdit').default);

// USERS
Vue.component('users', require('./components/users/Users').default);
Vue.component('users-search-form', require('./components/users/UsersSearchForm').default);
Vue.component('users-table', require('./components/users/UsersTable').default);
Vue.component('user-new', require('./components/users/UserNew').default);
Vue.component('user-edit', require('./components/users/UserEdit').default);
Vue.component('user-edit-form', require('./components/users/UserEditForm').default);
// IN CUSTOMERS
Vue.component('contact-card', require('./components/users/ContactCard').default);

// FILE MANAGER
Vue.component('file-manager', require('./components/file-manager/FileManager').default);
Vue.component('files-search-form', require('./components/file-manager/FilesSearchForm').default);
Vue.component('files-table', require('./components/file-manager/FilesTable').default);

// POSTS
Vue.component('posts-search-form', require('./components/posts/PostsSearchForm').default);
Vue.component('posts-table', require('./components/posts/PostsTable').default);
// POST
Vue.component('featured-posts', require('./components/posts/FeaturedPosts').default);
Vue.component('other-posts', require('./components/posts/OtherPosts').default);

// ? BLOG (FRONT VIEW)
Vue.component('thumbnail-post', require('./components/blogs/ThumbnailPost').default);

// BRANDS
Vue.component('brands-search-form', require('./components/brands/BrandsSearchForm').default);
Vue.component('brands-table', require('./components/brands/BrandsTable').default);
Vue.component('brand-new', require('./components/brands/BrandNew').default);
Vue.component('brand-edit', require('./components/brands/BrandEdit').default);

// CAR MODELS
Vue.component('car-models-search-form', require('./components/car_models/CarModelsSearchForm').default);
Vue.component('car-models-table', require('./components/car_models/CarModelsTable').default);
Vue.component('car-model-new', require('./components/car_models/CarModelNew').default);
Vue.component('car-model-edit', require('./components/car_models/CarModelEdit').default);

// CALLS
Vue.component('calls-search-form', require('./components/calls/CallsSearchForm').default);
Vue.component('calls-table', require('./components/calls/CallsTable').default);

// FAQS
Vue.component('faqs-search-form', require('./components/faqs/FaqsSearchForm').default);
Vue.component('faqs-table', require('./components/faqs/FaqsTable').default);
Vue.component('mobile-faqs-cards-table', require('./components/faqs/MobileFaqsCardsTable').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
    methods: {
        resetFields() {
            Object.assign(this.$data, this.$options.data.call(this));
        }
    }
});

const app = new Vue({
    store,
    el: '#app',
    router,
});
