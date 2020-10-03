<template>
  <div class="col-lg-12 col-md-12">
    <!-- Form Grid with Labels -->
    <form method="POST">
      <div class="form-group form-row">
        <div class="col-3">
          <label>Customer name</label>
          <select
            class="form-control"
            id="example-select"
            v-model="customer_filter"
          >
            <option value="0">Please select</option>
            <option
              v-for="customer in customers"
              :key="customer.id"
              :value="customer.id"
            >
              {{ customer.name }}
            </option>
          </select>
        </div>
        <div class="col-2">
          <label>Contract no</label>
          <select
            class="form-control"
            id="example-select2"
            v-model="contract_filter"
          >
            <option>Please select</option>
            <option
              v-for="contract in contracts"
              :key="contract.id"
              :value="contract.id"
            >
              {{ contract.contract_no }}
            </option>
          </select>
        </div>
        <div class="col-2">
          <label>Manager</label>
          <select
            class="form-control"
            id="example-select3"
            v-model="manager_filter"
          >
            <option>Please select</option>
            <option
              v-for="manager in managers"
              :key="manager.id"
              :value="manager.id"
            >
              {{ manager.name }}
            </option>
          </select>
        </div>
        <div class="col-1">
          <label>Region</label>
          <select
            class="form-control"
            id="example-select4"
            v-model="region_filter"
          >
            <option>Select</option>
            <option
              v-for="region in regions"
              :key="region.id"
              :value="region.id"
            >
              {{ region.name }}
            </option>
          </select>
        </div>
        <div class="col-3">
          <label>Date</label>
          <div class="form-group">
            <div
              class="input-daterange input-group"
              data-date-format="mm/dd/yyyy"
              data-week-start="1"
              data-autoclose="true"
              data-today-highlight="true"
            >
              <input
                type="text"
                class="form-control"
                id="example-daterange1"
                placeholder="From"
                data-week-start="1"
                data-autoclose="true"
                data-today-highlight="true"
                @input="checkExist($event, 'from')"
              />
              <input
                type="text"
                class="form-control"
                id="example-daterange2"
                placeholder="To"
                data-week-start="1"
                data-autoclose="true"
                data-today-highlight="true"
                @input="checkExist('adad', 'to')"
              />
            </div>
          </div>
        </div>
        <div class="col-1" style="margin-top: 30px">
          <span class="btn btn-primary" @click="sendFilter">
            <i class="fa fa-search"></i>
          </span>
        </div>
      </div>
    </form>
    <!-- END Form Grid with Labels -->
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      customer_filter: "",
      contract_filter: "",
      manager_filter: "",
      region_filter: "",
      data_from_filter: "",
      data_to_filter: "",
      customers: [],
      contracts: [],
      managers: [],
      regions: [],
    };
  },
  mounted: function () {
    this.getData();
  },
  methods: {
    getData: function () {
      var vue = this;
      axios({
        method: "get",
        url: "/analyzer/get",
      }).then(function (response) {
        console.log(response.data);
        vue.customers = response.data.customers;
        vue.contracts = response.data.contracts;
        vue.managers = response.data.managers;
        vue.regions = response.data.regions;
      });
    },
    checkExist(event, date) {
      if (date === "to") {
        vue.data_to_filter = event.target.value;
        console.log(vue.data_to_filter);
      } else {
        vue.data_from_filter = event.target.value;
        console.log(vue.data_from_filter);
      }
    },
    sendFilter: function () {
      var vue = this;
      axios({
        method: "post",
        url: "analyzer/get-analyze",
        data: {
          customer: vue.customer_filter,
          contract: vue.contract_filter,
          manager: vue.manager_filter,
          region: vue.region_filter,
          data_from: vue.data_from_filter,
          data_to: vue.data_to_filter,
        },
      }).then(function (response) {
        // console.log(response.data);
      });
    },
  },
};
</script>
