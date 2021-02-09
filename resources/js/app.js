/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-moment'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('spinner', require('./components/Spinner.vue').default);
Vue.component('card-counter', require('./components/CardCounter').default);
Vue.component('delete-modal', require('./components/DeleteModal').default);

// TICKETS
Vue.component('tickets', require('./components/tickets/Tickets').default);
Vue.component('tickets-table', require('./components/tickets/TicketsTable').default);
Vue.component('tickets-search-form', require('./components/tickets/TicketsSearchForm').default);
    // CREATE TICKET
        Vue.component('tickets-create', require('./components/tickets/TicketsCreateComponent').default);
        Vue.component('calls-modal', require('./components/CallsModalComponent').default);
    // VIEW TICKET 
        Vue.component('ticket-view-info', require('./components/tickets/TicketViewInfoComponent').default);
        Vue.component('ticket-comment', require('./components/tickets/TicketCommentComponent').default);
        Vue.component('ticket-new-coment', require('./components/tickets/TicketNewCommentComponent').default);
        Vue.component('ticket-view-calls', require('./components/tickets/TicketViewCallsComponent').default);

// DEPARTMENTS
Vue.component('departments', require('./components/departments/Departments').default);
Vue.component('departments-search-form', require('./components/departments/DepartmentsSearchForm').default);
Vue.component('departments-table', require('./components/departments/DepartmentsTable').default);
Vue.component('department-new', require('./components/departments/DepartmentNew').default);
Vue.component('department-edit', require('./components/departments/DepartmentEdit').default);
Vue.component('department-assign-user', require('./components/departments/DepartmentAssignUser').default);
Vue.component('department-assign-form', require('./components/departments/DepartmentAssignForm').default);
Vue.component('department-assign-table', require('./components/departments/DepartmentAssignTable').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
