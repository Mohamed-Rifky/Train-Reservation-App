/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
require("admin-lte");
const Swal  = require('sweetalert2');

import vue_pagination from 'laravel-vue-pagination';
Vue.use(vue_pagination);
Vue.component('pagination',vue_pagination);
$(document).ready(function () {
    $(".alert").fadeOut(5000);
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('time_component', require('./components/time/time.vue').default);
Vue.component('trains', require('./components/trains/trains').default);
Vue.component('bookings', require('./components/trains/bookings').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
