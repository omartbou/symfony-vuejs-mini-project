import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Create from '../components/Product/Create.vue';
import Detail from '../components/Product/Detail.vue';
import Update from '../components/Product/Update.vue';
import Login from '../components/User/Login.vue';
import Register from '../components/User/Register.vue';

// Define routes
const routes = [
    {
        path: '/home',
        name: 'home',
        component: Home
    },

    {
        path: '/create',
        name: 'product-create',
        component: Create
    },
    {
        path: '/detail/:id',
        name: 'product-details',
        component: Detail
    },
    {
        path: '/edit/:id',
        name: 'product-update',
        component: Update
    },
    {
        path: '/login',
        name: 'user-login',
        component: Login
    },
    {
        path: '/register',
        name: 'user-register',
        component: Register,
    },

];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
