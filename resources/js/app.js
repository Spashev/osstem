require("./bootstrap");

window.Vue = require("vue");

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
Vue.component(
    "sms-history-component",
    require("./components/SmsHistoryComponent.vue").default
);
Vue.component(
    "progress-bar",
    require("./components/ProgressBarComponent.vue").default
)
Vue.component(
    "customer-progress-bar",
    require("./components/ProgressBarComponentCustomer.vue").default
)

import VueGoodTablePlugin from "vue-good-table";
import "vue-good-table/dist/vue-good-table.css";
import Chartkick from "vue-chartkick";
import Chart from "chart.js";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Select2 from "v-select2-component";

var filter = function(text, length, clamp) {
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(Chartkick.use(Chart));
Vue.use(VueGoodTablePlugin);
Vue.use(DatePicker);
Vue.use(Select2);


const app = new Vue({
    el: "#app"
});