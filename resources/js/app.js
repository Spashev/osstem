/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "notify-component",
    require("./components/NotificationComponent.vue").default
);
Vue.component(
    "analuze-component",
    require("./components/AnalyzeComponent.vue").default
);
Vue.component(
    "table-component",
    require("./components/TableComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueGoodTablePlugin from "vue-good-table";
// import the styles
import "vue-good-table/dist/vue-good-table.css";

//Chart
import Chartkick from "vue-chartkick";
import Chart from "chart.js";

//DatePicker
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

//Select2
import Select2 from "v-select2-component";

Vue.use(Chartkick.use(Chart));
Vue.use(VueGoodTablePlugin);
Vue.use(DatePicker);
Vue.use(Select2);


const app = new Vue({
    el: "#app"
});