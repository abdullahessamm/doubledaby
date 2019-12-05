import Vue from 'vue';
import router from './router';
import dashboard from './components/DashBoard.vue';
//import Momeny from 'moment';
import Chart from 'chart.js';
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

const app = new Vue({
    el: '#app',
    components: {
        dashboard
    },

    router,
    
});
