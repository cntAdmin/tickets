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
import FileManager from './file-manager/FileManager';
import Post from './posts/Post';
import PostCreate from './posts/PostCreate';
import PostEdit from './posts/PostEdit';
import PostShow from './posts/PostShow';
import Brands from './brands/Brands';
import CarModels from './car_models/CarModels';

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
        // * ADMIN ROUTES
        {
            path: '/admin/ticket',
            name: 'ticket.index', 
            component: Tickets
        },
            {
                path: '/admin/ticket/create/:customer_id?',
                name: 'ticket.create', 
                component: TicketsCreate
            },
            {
                path: '/admin/ticket/:ticketID',
                name: 'ticket.show', 
                component: TicketViewInfo,
                props: true
            },
            {
                path: '/admin/ticket/:ticketID/edit',
                name: 'ticket.edit', 
                component: TicketEdit,
                props: true
            },
        {
            path: '/admin/brand',
            name: 'brand.index', 
            component: Brands
        },
        {
            path: '/admin/car-model',
            name: 'car_model.index', 
            component: CarModels
        },
        {
            path: '/admin/department',
            name: 'department.index', 
            component: Departments
        },
        {
            path: '/admin/customer',
            name: 'customer.index', 
            component: Customers
        },
        {
            path: '/admin/user',
            name: 'user.index', 
            component: Users
        },
        {
            path: '/admin/file-manager',
            name: 'file_manager.index', 
            component: FileManager
        },
        {
            path: '/admin/post',
            name: 'post.index',
            component: Post
        },
            {
                path: '/admin/post/create',
                name: 'post.create',
                component: PostCreate
            },
            {
                path: '/admin/post/:post/edit',
                name: 'post.edit',
                component: PostEdit,
                props: true
            },
            {
                path: '/admin/post/:post',
                name: 'post.show',
                component: PostShow,
                props: true
            },
        // * ADMIN ROUTES

    ],
});
export default router;