import Vue from 'vue';
import VueRouter from 'vue-router';

// COMPONENTS
import Tickets from "./tickets/Tickets";
import TicketViewInfo from "./tickets/TicketViewInfoComponent";
import TicketsCreate from "./tickets/TicketsCreateComponent";
import TicketEdit from "./tickets/TicketEditComponent";
import Departments from "./departments/Departments";
import Customers from "./customers/Customers";
import Users from "./users/Users";
import Faqs from "./faqs/Faqs";
import FileManager from './file-manager/FileManager';
import Post from './posts/Post';
import PostCreate from './posts/PostCreate';
import PostEdit from './posts/PostEdit';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '*',
            name: 'all', 
            component: Tickets
        },
        {
            path: '/',
            name: 'home', 
            component: Tickets
        },
        {
            path: '/ticket',
            name: 'ticket.index', 
            component: Tickets
        },
            {
                path: '/ticket/create/:customer_id?',
                name: 'ticket.create', 
                component: TicketsCreate
            },
            {
                path: '/ticket/:ticketID',
                name: 'ticket.show', 
                component: TicketViewInfo,
                props: true
            },
            {
                path: '/ticket/:ticketID/edit',
                name: 'ticket.edit', 
                component: TicketEdit,
                props: true
            },
        {
            path: '/department',
            name: 'department.index', 
            component: Departments
        },
        {
            path: '/customer',
            name: 'customer.index', 
            component: Customers
        },
        {
            path: '/users',
            name: 'users.index', 
            component: Users
        },
        {
            path: '/faqs',
            name: 'faqs.index', 
            component: Faqs
        },
        {
            path: '/file-manager',
            name: 'file_manager.index', 
            component: FileManager
        },
        {
            path: '/post',
            name: 'post.index',
            component: Post
        },
            {
                path: '/post/create',
                name: 'post.create',
                component: PostCreate
            },
            {
                path: '/post/:post',
                name: 'post.show',
                component: PostEdit,
                props: true
            },
    ],
});
export default router;