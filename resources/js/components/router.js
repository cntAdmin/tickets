import Vue from 'vue';
import VueRouter from 'vue-router';

// COMPONENTS
import Tickets from "./tickets/Tickets";
import TicketViewInfo from "./tickets/TicketViewInfoComponent";
import TicketsCreate from "./tickets/TicketsCreateComponent";
import TicketEdit from "./tickets/TicketEditComponent";

import Posts from './posts/Posts';
import Post from './posts/Post';

import Users from "./users/Users";
import Profile from './users/Profile';

import Departments from "./departments/Departments";
import Customers from "./customers/Customers";
import FileManager from './file-manager/FileManager';
import PostCreate from './posts/PostCreate';
import PostEdit from './posts/PostEdit';
import Brands from './brands/Brands';
import CarModels from './car_models/CarModels';
import Calls from './calls/Calls';
import Blogs from './blogs/Blogs';

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
            path: '/admin/incidencia',
            name: 'ticket.index', 
            component: Tickets
        },
            {
                path: '/admin/incidencia/create/:customer_id?',
                name: 'ticket.create', 
                component: TicketsCreate
            },
            {
                path: '/admin/incidencia/:ticketID',
                name: 'ticket.show', 
                component: TicketViewInfo,
                props: true
            },
            {
                path: '/admin/incidencia/:ticketID/edit',
                name: 'ticket.edit', 
                component: TicketEdit,
                props: true
            },
        {
            path: '/admin/call',
            name: 'call.index', 
            component: Calls
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
            path: '/admin/blog',
            name: 'post.index',
            component: Posts
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
                component: Post,
                props: true
            },
        // * ADMIN ROUTES
        // * USERS ROUTES.
        {
            path: '/blog',
            name: 'users-blog.index',
            component: Blogs
        },
        {
            path: '/profile/:user',
            name: 'profile.index',
            component: Profile,
            props: true
        },


    ],
});
export default router;