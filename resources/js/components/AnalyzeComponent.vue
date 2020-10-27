<template>
  <div class="col-lg-12 col-md-12">
    <div>
      <form method="POST">
        <div class="form-group form-row row text-center">
          <div class="col-sm-2">
            <label>Manager</label>
            <Select2
              id="manager"
              name="manager"
              placeholder="Select manager"
              v-model="manager_filter"
              :options="managers"
              @change="managerEvent($event)"
              :disabled="disabled == 1"
            />
          </div>
          <div class="col-sm-2">
            <label>Region</label>
            <Select2
              id="region"
              name="region"
              placeholder="Select region"
              v-model="region_filter"
              :options="regions"
              @change="regionEvent($event)"
            />
          </div>
          <div class="col-sm-3">
            <label>Customer name</label>
            <select2
              id="customer"
              name="customer"
              placeholder="Select customer"
              v-model="customer_filter"
              :options="customers"
              @change="customerEvent($event)"
              @select="disabled = 1"
            >
              <option value="">Select one</option>
            </select2>
          </div>
          <div class="col-sm-2">
            <label>Contract no</label>
            <Select2
              id="contract"
              name="contract"
              placeholder="Select contract"
              v-model="contract_filter"
              :options="contracts"
              @change="contractEvent($event)"
            />
          </div>
          <div class="col-sm-3">
            <label>Date</label>
            <div class="form-group">
              <div class="input-group">
                <date-picker
                  v-model="data_filter"
                  type="date"
                  range
                  valueType="format"
                  placeholder="Select date range"
                  @change="dateEvent($event)"
                ></date-picker>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div v-if="chart_flag" class="text-center">
      <h3 class="block-title ml-3">{{ chart_title }}</h3>
      <pie-chart
        :data="chart_data"
        :download="{ background: '#fff' }"
      ></pie-chart>
    </div>
    <div v-if="manager_flag" class="text-center">
      <area-chart :data="chart_data" ytitle="Contracts"></area-chart>
    </div>
    <div v-if="column_flag" class="text-center">
      <column-chart :data="chart_data" ytitle="Managers"></column-chart>
    </div>
    <div v-if="line_flag" class="text-center">
      <line-chart
        :data="chart_data"
        xtitle="Date"
        ytitle="Payments"
      ></line-chart>
    </div>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Select2 from "v-select2-component";

export default {
  name: "analyze",
  components: {
    DatePicker,
    Select2,
  },
  data: function () {
    return {
      customer_filter: "",
      contract_filter: "",
      manager_filter: "",
      region_filter: "",
      data_filter: "",
      customers: [],
      contracts: [],
      managers: [],
      regions: [],
      payments: [],
      myValue: "",
      myOptions: [],
      chart_flag: false,
      manager_flag: false,
      column_flag: false,
      line_flag: false,
      disabled: 0,
      chart_title: "",
      chart_data: [],
    };
  },
  mounted: function () {
    this.getData();
  },
  methods: {
    setup() {},
    customerEvent(val) {
      var vue = this;
      axios({
        method: "get",
        url: "get-contract/" + val,
      }).then(function (response) {
        vue.contracts = response.data;
        vue.chart_title = response.data.pop().name;
        vue.contract_flag = true;
        vue.chart_data = response.data.map(function (item) {
          return [item.text, 1];
        });
        vue.manager_flag = false;
        vue.line_flag = false;
        vue.chart_flag = true;
        vue.column_flag = false;
      });
    },
    contractEvent(val) {
      var vue = this;
      axios({
        method: "get",
        url: "get-payments/" + val,
      }).then(function (response) {
        vue.payments = response.data;
        vue.chart_data = response.data.map(function (item) {
          return [item.hash.substr(2, item.hash.length - 6), item.paid];
        });
        vue.manager_flag = false;
        vue.chart_flag = true;
        vue.line_flag = false;
        vue.column_flag = false;
      });
    },
    managerEvent(val) {
      var vue = this;
      axios({
        method: "get",
        url: "get-manager/" + val,
      }).then(function (response) {
        vue.chart_data = response.data;
        vue.chart_flag = false;
        vue.manager_flag = true;
        vue.line_flag = false;
        vue.column_flag = false;
      });
    },
    regionEvent(val) {
      var vue = this;
      axios({
        method: "get",
        url: "get-region/" + val,
      }).then(function (response) {
        vue.customers = response.data;
        vue.managers = [];
        vue.contracts = [];
        vue.chart_data = response.data.map(function (item) {
          return [item.text, item.id];
        });
        vue.column_flag = true;
        vue.manager_flag = false;
        vue.line_flag = false;
        vue.chart_flag = false;
        vue.disabled = 0;
      });
    },
    dateEvent(val) {
      var vue = this;
      const form = new FormData();
      form.append("from", val[0]);
      form.append("to", val[1]);
      axios({
        method: "post",
        url: "get-date/",
        data: form,
      }).then(function (response) {
        console.log(response.data);
        vue.chart_data = response.data;
        vue.column_flag = false;
        vue.manager_flag = false;
        vue.line_flag = true;
        vue.chart_flag = false;
      });
    },
    getData: function () {
      var vue = this;
      axios({
        method: "get",
        url: "/analyzer/get",
      }).then(function (response) {
        vue.customers = response.data.customers;
        vue.managers = response.data.managers;
        vue.regions = response.data.regions;
      });
    },
  },
};
</script>
