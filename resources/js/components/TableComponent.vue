<template>
    <div class="block m-3">
        <div id="loader" v-if="show_loader"></div>
        <div class="block-header animate-bottom" id="myDiv">
            <h3 class="block-title">Overdue</h3>
            <div
                class="alert alert-success d-flex align-items-center"
                role="alert"
                v-if="sendSmsRegion"
            >
                <div class="flex-00-auto">
                    <i class="fa fa-fw fa-check"></i>
                </div>
                <div class="flex-fill ml-3">
                    <p class="mb-0">{{ region_send_sms }}!</p>
                </div>
            </div>
            <div class="block-options">
                <div class="block-options-item">
                    <code>table</code>
                </div>
            </div>
        </div>
        <div class="block-content">
            <form method="POST">
                <div class="form-group form-row row text-center">
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

            <div>
                <button
                    type="button"
                    class="btn btn-outline-info mr-1 float-right mb-2"
                    onclick="exportTableToCSV('sms_delay.csv')"
                    v-if="table_flag"
                >
                    <i class="fa fa-fw fa-download mr-1"></i> Download
                </button>
                <button
                    class="btn btn-outline-secondary mr-1 float-right mb-2"
                    @click="sendRegionSms(region_filter)"
                    v-if="table_flag"
                >
                    <i class="fa fa-fw fa-paper-plane mr-1"></i> Send
                </button>
                <table
                    class="table table-bordered table-vcenter"
                    v-if="table_flag"
                >
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px">#</th>
                            <th>Customer_code</th>
                            <th>Customer name</th>
                            <th>Amount</th>
                            <th>Total remain</th>
                            <th>Total paid</th>
                            <th
                                class="d-none d-sm-table-cell"
                                style="width: 15%"
                            >
                                <div
                                    class="custom-control custom-checkbox custom-checkbox-rounded-circle custom-control-success mb-1"
                                >
                                    <input
                                        @click="selectAll($event.target)"
                                        type="checkbox"
                                        class="custom-control-input confirmation-select"
                                        id="example-cb-custom-circle2"
                                        name="confirmation"
                                        checked=""
                                    />
                                    <label
                                        class="custom-control-label"
                                        for="example-cb-custom-circle2"
                                        >Confirmation</label
                                    >
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in payments" v-bind:key="payment.id">
                            <th class="text-center" scope="row">
                                {{ payment.id }}
                            </th>
                            <td class="font-w600 font-size-sm">
                                <a href="javascript:void(0);">{{
                                    payment.customer_code
                                }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <a
                                    href="javascript:void(0);"
                                    @click="
                                        getContractData(payment.customer_code)
                                    "
                                >
                                    {{ payment.text }}
                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>{{ payment.amount }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>{{ payment.total_remain }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>{{ payment.total_paid }}</span>
                            </td>
                            <td class="text-center">
                                <div
                                    v-if="payment.sms_status == 'on'"
                                    class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1"
                                >
                                    <input
                                        type="checkbox"
                                        class="custom-control-input selectAll"
                                        v-bind:data-id="payment.customer_code"
                                        :id="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
                                        :name="'sms_status_' + payment.id"
                                        checked=""
                                        v-on:change="
                                            SmsCustomerEvent(
                                                payment.customer_code
                                            )
                                        "
                                    />
                                    <label
                                        class="custom-control-label"
                                        :for="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
                                        >SMS</label
                                    >
                                </div>
                                <div
                                    v-else
                                    class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1"
                                >
                                    <input
                                        type="checkbox"
                                        class="custom-control-input selectAll"
                                        v-bind:data-id="payment.customer_code"
                                        :id="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
                                        :name="'sms_status_' + payment.id"
                                        v-on:change="
                                            SmsCustomerEvent(
                                                payment.customer_code
                                            )
                                        "
                                    />
                                    <label
                                        class="custom-control-label"
                                        for="example-cb-custom-success-lg2"
                                        >SMS</label
                                    >
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table
                    class="table table-bordered table-vcenter"
                    v-if="customer_flag"
                >
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px">#</th>
                            <th>Contract_no</th>
                            <th class="text-center" style="width: 100px">
                                Remain
                            </th>
                            <th class="text-center" style="width: 100px">
                                Paid
                            </th>
                            <th
                                class="d-none d-sm-table-cell"
                                style="width: 15%"
                            >
                                Deadline
                            </th>
                            <th
                                class="d-none d-sm-table-cell"
                                style="width: 15%"
                            >
                                Confirmation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="payment in payments"
                            v-bind:key="payment.contract_no"
                        >
                            <th class="text-center" scope="row">
                                {{ payment.id }}
                            </th>
                            <td class="font-w600 font-size-sm">
                                <a
                                    href="javascript:void(0);"
                                    @click="
                                        getCustomerData(payment.contract_no)
                                    "
                                    >{{ payment.contract_no }}</a
                                >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>
                                    {{ payment.total_remain }}
                                </span>
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
                                        :id="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
                                        :name="'sms_status_' + payment.id"
                                        checked
                                        @click="
                                            smsStatusEvent(payment.contract_no)
                                        "
                                    />
                                    <label
                                        class="custom-control-label"
                                        :for="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
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
                                        :id="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
                                        :name="'sms_status_' + payment.id"
                                        @click="
                                            smsStatusEvent(payment.contract_no)
                                        "
                                    />
                                    <label
                                        class="custom-control-label"
                                        :for="
                                            'example-sw-custom-success-lg2_' +
                                                payment.id
                                        "
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header  bg-primary-dark">
                            <h5 class="block-title" id="exampleModalLabel">
                                Modal title
                            </h5>
                            <div class="block-options">
                                <button
                                    type="button"
                                    class="close btn-block-option"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                >
                                    <span aria-hidden="true"
                                        ><i class="fa fa-fw fa-times"></i
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body font-size-sm">
                        <table class="table table-bordered table-vcenter m-2">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px">
                                        #
                                    </th>
                                    <th>Contract_no</th>
                                    <th
                                        class="text-center"
                                        style="width: 100px"
                                    >
                                        Remain
                                    </th>
                                    <th
                                        class="text-center"
                                        style="width: 100px"
                                    >
                                        Paid
                                    </th>
                                    <th
                                        class="d-none d-sm-table-cell"
                                        style="width: 15%"
                                    >
                                        Deadline
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="payment in modal_data"
                                    v-bind:key="payment.id"
                                >
                                    <th class="text-center" scope="row">
                                        {{ payment.id }}
                                    </th>
                                    <td class="font-w600 font-size-sm">
                                        <a href="javascript:void(0);">{{
                                            payment.contract_no
                                        }}</a>
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
        Select2
    },
    data: function() {
        return {
            customer_filter: "",
            region_filter: "",
            data_filter: "",
            region_send_sms: "",
            customers: [],
            regions: [],
            payments: [],
            modal_data: [],
            table_flag: false,
            show_loader: false,
            modal_flag: false,
            customer_flag: false,
            sendSmsRegion: false
        };
    },
    mounted: function() {
        this.getData();
    },
    methods: {
        selectAll(val) {
            this.show_loader = false;
            this.modal_flag = false;
            var checkbox = $("body input:checkbox");
            if (checkbox.is(":checked")) {
                checkbox.prop("checked", false);
            } else {
                checkbox.prop("checked", true);
            }
            var status = [];
            for (var item of Array.from(checkbox)) {
                status.push(item.getAttribute("data-id"));
            }
            this.SmsCustomerEvent(status);
        },
        getCustomerData(val) {
            var vue = this;
            vue.modal_flag = true;
            const form = new FormData();
            form.append("contract_no", val);
            axios({
                method: "post",
                url: "sms/customer-data/",
                data: form
            }).then(function(response) {
                if (response.data.hasOwnProperty("msg")) {
                    alert(response.data.msg);
                    vue.table_flag = false;
                } else {
                    $("#exampleModal").modal("show");
                    vue.table_flag = false;
                    vue.customer_flag = true;
                    vue.modal_data = response.data;
                }
                console.log(response.data);
            });
        },
        getContractData(val) {
            var vue = this;
            vue.modal_flag = true;
            const form = new FormData();
            form.append("customer_id", val);
            axios({
                method: "post",
                url: "sms/contract-data/",
                data: form
            }).then(function(response) {
                if (response.data.hasOwnProperty("msg")) {
                    alert(response.data.msg);
                    vue.table_flag = false;
                } else {
                    $("#exampleModal").modal("show");
                    vue.table_flag = true;
                    console.log(response.data);
                    vue.modal_data = response.data;
                }
            });
        },
        customerEvent(val) {
            this.show_loader = true;
            this.table_flag = false;
            var vue = this;
            axios({
                method: "get",
                url: "sms/get-customer/" + val
            }).then(function(response) {
                vue.payments = response.data;
                vue.show_loader = false;
                vue.customer_flag = true;
            });
        },
        regionEvent(val) {
            this.customer_flag = false;
            this.show_loader = true;
            var vue = this;
            axios({
                method: "get",
                url: "sms/get-region/" + val
            }).then(function(response) {
                console.log(response.data);
                if (response.data.hasOwnProperty("msg")) {
                    alert(response.data.msg);
                    vue.table_flag = false;
                    vue.show_loader = false;
                } else {
                    vue.show_loader = false;
                    vue.payments = response.data;
                    vue.customers = response.data;
                    vue.table_flag = true;
                    $("input:checkbox.confirmation-select").prop(
                        "checked",
                        true
                    );
                }
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
                data: form
            }).then(function(response) {
                vue.show_loader = false;
                vue.payments = response.data;
                vue.table_flag = true;
                vue.modal_flag = false;
            });
        },
        smsStatusEvent(val) {
            var vue = this;
            console.log(val);
            const form = new FormData();
            form.append("contract_code", val);
            form.append("type", "contract");
            axios({
                method: "post",
                url: "sms/status/",
                data: form
            }).then(function(response) {
                alert(response.data.msg);
                vue.modal_flag = false;
            });
        },
        SmsCustomerEvent(val) {
            var vue = this;
            const form = new FormData();
            form.append("customer_code", val);
            form.append("type", "customer");
            axios({
                method: "post",
                url: "sms/status/",
                data: form
            }).then(function(response) {
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
                data: form
            }).then(function(response) {
                console.log(response.data);
                vue.modal_flag = false;
                vue.customer_flag = false;
                $("#exampleModal").modal("hide");
            });
        },
        sendRegionSms(val) {
            var vue = this;
            vue.show_loader = true;
            axios({
                method: "get",
                url: "send/sms/" + val
            }).then(function(response) {
                vue.show_loader = false;
                vue.region_send_sms = response.data.msg;
                vue.payments = [];
                vue.table_flag = false;
            });
        },
        getData: function() {
            var vue = this;
            axios({
                method: "get",
                url: "/sms/get"
            }).then(function(response) {
                vue.customers = response.data.customers;
                vue.regions = response.data.regions;
                vue.modal_flag = false;
            });
        }
    }
};
</script>
