// import './bootstrap.js';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router.js'
import Login from './components/User/Login.vue';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const app = createApp(App)
    app.component('Login'.Login);
    app.use(router).mount('#app');


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
