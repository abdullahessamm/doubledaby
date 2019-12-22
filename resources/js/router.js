import Vue from 'vue';
import VueRouter from 'vue-router';
import DashboardInterface from './components/dashboard/interface.vue';
import Store from './components/dashboard/store.vue';
import Sales from './components/dashboard/sales.vue';
import Admins from './components/dashboard/admins.vue';

Vue.use(VueRouter);

var routes = [
    //All routes.
    {
        name: 'Interface',
        path: '/dashboard',
        component: DashboardInterface,
    },

    {
        name: 'Store',
        path: '/dashboard/store',
        component: Store
    },
    {
        name: 'Sales',
        path: '/dashboard/sales',
        component: Sales
    },
    {
        name: 'Admins',
        path: '/dashboard/admins',
        component: Admins,
    }
];

export default new VueRouter({
    routes,
    mode: 'history'
});