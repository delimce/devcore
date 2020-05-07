/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.api_url = process.env.MIX_APP_URL;
window.imgPublicPath = window.api_url + '/assets/img/';

window.Vue = require('vue');
window.axios = require('axios');

/**
 * GLOBAL VARIABLES
 */
Vue.prototype.$imagePath = window.api_url + '/assets/img/';

/**
 * Axios interceptors for http requests& responses
 */
axios.interceptors.request.use(
    function (config) {
        return config;
    }, function (error) {
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    function (response) {
        return response;
    }, function (error) {
        return Promise.reject(error);
    }
);

/**
 * Vuelidate
 * method to validate app forms
 */
import Vuelidate from 'vuelidate'
window.Vue.use(Vuelidate)
/** modals */
import VModal from 'vue-js-modal'
window.Vue.use(VModal, { dynamic: true })
/** spinner */
import Preloader from 'vue-spinner/src/BeatLoader.vue'
window.Vue.component('PreLoader', Preloader);
/** router */
import VueRouter from 'vue-router';
import { routes } from './routes';
Vue.use(VueRouter);

const router = new VueRouter({
    base: '/admin/', 
    mode: 'history',
    routes
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
