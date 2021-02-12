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
    ],
});
export default router;