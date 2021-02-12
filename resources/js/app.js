/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 import router from './components/router';
 import {RichTextEditorPlugin} from "@syncfusion/ej2-vue-richtexteditor";
 import { VueMoment } from "vue-moment";
 import vSelect from 'vue-select';

//  console.log(router)
 require('./bootstrap');
 
 window.Vue = require('vue');

 Vue.use(require('vue-moment'));
 Vue.use(RichTextEditorPlugin);
 
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
 Vue.component('sidebar', require('./components/Sidebar').default);
 Vue.component('navbar', require('./components/Navbar').default);
 Vue.component('pagination', require('laravel-vue-pagination'));
 Vue.component('spinner', require('./components/Spinner.vue').default);
 Vue.component('card-counter', require('./components/CardCounter').default);
 Vue.component('delete-modal', require('./components/DeleteModal').default);
 Vue.component('vue-select', vSelect)

// TICKETS
Vue.component('tickets', require('./components/tickets/Tickets').default);
Vue.component('tickets-table', require('./components/tickets/TicketsTable').default);
Vue.component('tickets-search-form', require('./components/tickets/TicketsSearchForm').default);
// CREATE TICKET
    Vue.component('tickets-create', require('./components/tickets/TicketsCreateComponent').default);
    Vue.component('calls-modal', require('./components/CallsModalComponent').default);
// VIEW TICKET 
    Vue.component('ticket-view-info', require('./components/tickets/TicketViewInfoComponent').default);
    Vue.component('ticket-comments', require('./components/tickets/TicketCommentsComponent').default);
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


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});
