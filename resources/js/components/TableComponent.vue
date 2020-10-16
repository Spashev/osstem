<template>
  <div class="block m-3">
    <div id="loader" v-if="show_loader"></div>
    <div class="block-header animate-bottom" id="myDiv">
      <h3 class="block-title">Expiration Table</h3>
      <div class="block-options">
        <div class="block-options-item">
          <code>Sms notifications</code>
        </div>
      </div>
    </div>
    <div class="block-content">
      <form method="POST">
        <div class="form-group form-row row text-center">
          <div class="col-sm-4">
            <label>Customer name</label>
            <select2
              id="customer"
              name="customer"
              placeholder="Select customer"
              v-model="customer_filter"
              :options="customers"
              @change="customerEvent($event)"
            >
              <option value="">Select one</option>
            </select2>
          </div>
          <div class="col-sm-4">
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
          <div class="col-sm-4">
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

      <div v-if="table_flag">
        <button
          class="btn btn-outline-info float-right mb-2"
          onclick="exportTableToCSV('sms.csv')"
        >
          <i class="si si-cloud-download"></i>
        </button>
        <table class="table table-bordered table-vcenter">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px">#</th>
              <th>Contract_no</th>
              <th class="text-center" style="width: 100px">Remain</th>
              <th class="text-center" style="width: 100px">Paid</th>
              <th class="d-none d-sm-table-cell" style="width: 15%">
                Deadline
              </th>
              <th class="d-none d-sm-table-cell" style="width: 15%">
                Confirmation
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in payments" v-bind:key="payment.id">
              <th class="text-center" scope="row">
                {{ payment.id }}
              </th>
              <td class="font-w600 font-size-sm">
                <a
                  href="javascript:void(0);"
                  @click="getContractData(payment.contract_no)"
                  >{{ payment.contract_no }}</a
                >
              </td>
              <td class="d-none d-sm-table-cell">
                <span>{{ payment.total_remain }}</span>
              </td>
              <td class="d-none d-sm-table-cell">
                <span>{{ payment.total_paid }}</span>
              </td>
              <td class="d-none d-sm-table-cell">
                <span>{{ payment.deadline }}</span>
              </td>
              <td class="text-center">
                <div
                  v-if="payment.sms_status == 'on'"
                  class="custom-control custom-switch custom-control-success custom-control-lg mb-2"
                >
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    :id="'example-sw-custom-success-lg2_' + payment.id"
                    :name="'sms_status_' + payment.id"
                    checked
                    @click="smsStatusEvent(payment.contract_no)"
                  />
                  <label
                    class="custom-control-label"
                    :for="'example-sw-custom-success-lg2_' + payment.id"
                    >SMS</label
                  >
                </div>
                <div
                  v-else
                  class="custom-control custom-switch custom-control-success custom-control-lg mb-2"
                >
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    :id="'example-sw-custom-success-lg2_' + payment.id"
                    :name="'sms_status_' + payment.id"
                    @click="smsStatusEvent(payment.contract_no)"
                  />
                  <label
                    class="custom-control-label"
                    :for="'example-sw-custom-success-lg2_' + payment.id"
                    >SMS</label
                  >
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      v-if="modal_flag"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-vcenter m-2">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px">#</th>
                  <th>Contract_no</th>
                  <th class="text-center" style="width: 100px">Remain</th>
                  <th class="text-center" style="width: 100px">Paid</th>
                  <th class="d-none d-sm-table-cell" style="width: 15%">
                    Deadline
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="payment in modal_data" v-bind:key="payment.id">
                  <th class="text-center" scope="row">
                    {{ payment.id }}
                  </th>
                  <td class="font-w600 font-size-sm">
                    <a href="javascript:void(0);">{{ payment.contract_no }}</a>
                  </td>
                  <td class="d-none d-sm-table-cell">
                    <span>{{ payment.total_remain }}</span>
                  </td>
                  <td class="d-none d-sm-table-cell">
                    <span>{{ payment.total_paid }}</span>
                  </td>
                  <td class="d-none d-sm-table-cell">
                    <span>{{ payment.deadline }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-warning"
              @click="sendSms(modal_data[0].contract_no)"
            >
              Send sms
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Select2 from "v-select2-component";

export default {
  components: {
    DatePicker,
    Select2,
  },
  data: function () {
    return {
      customer_filter: "",
      region_filter: "",
      data_filter: "",
      customers: [],
      regions: [],
      payments: [],
      modal_data: [],
      table_flag: false,
      show_loader: false,
      modal_flag: false,
    };
  },
  mounted: function () {
    this.getData();
  },
  methods: {
    getContractData(val) {
      var vue = this;
      const form = new FormData();
      form.append("from", val);
      axios({
        method: "post",
        url: "sms/contract-data/",
        data: form,
      }).then(function (response) {
        vue.modal_flag = true;
        console.log(response.data);
        vue.modal_data = response.data;
        $("#exampleModal").modal("show");
      });
    },
    customerEvent(val) {
      var vue = this;
      axios({
        method: "get",
        url: "sms/get-customer/" + val,
      }).then(function (response) {
        vue.modal_flag = false;
        vue.payments = response.data;
        vue.table_flag = true;
      });
    },
    regionEvent(val) {
      this.show_loader = true;
      var vue = this;
      axios({
        method: "get",
        url: "sms/get-region/" + val,
      }).then(function (response) {
        if (response.data[0].hasOwnProperty("msg")) {
          alert(response.data[0].msg);
          vue.table_flag = false;
        } else {
          vue.payments = response.data[0];
          vue.customers = response.data[1];
          vue.table_flag = true;
        }
        vue.show_loader = false;
        vue.modal_flag = false;
      });
    },
    dateEvent(val) {
      this.show_loader = true;
      var vue = this;
      const form = new FormData();
      form.append("from", val[0]);
      form.append("to", val[1]);
      axios({
        method: "post",
        url: "sms/get-date/",
        data: form,
      }).then(function (response) {
        vue.show_loader = false;
        vue.payments = response.data;
        vue.table_flag = true;
        vue.modal_flag = false;
      });
    },
    getData: function () {
      var vue = this;
      axios({
        method: "get",
        url: "/sms/get",
      }).then(function (response) {
        vue.customers = response.data.customers;
        vue.regions = response.data.regions;
        vue.table_flag = true;
        vue.modal_flag = false;
      });
    },
    smsStatusEvent(val) {
      var vue = this;
      const form = new FormData();
      form.append("from", val);
      axios({
        method: "post",
        url: "sms/status/",
        data: form,
      }).then(function (response) {
        alert(response.data.msg);
        vue.modal_flag = false;
      });
    },
    sendSms(val) {
      var vue = this;
      const form = new FormData();
      form.append("contract_no", val);
      axios({
        method: "post",
        url: "send/sms/",
        data: form,
      }).then(function (response) {
        console.log(response.data);
        vue.modal_flag = false;
      });
    },
  },
};
</script>
