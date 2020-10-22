<template>
    <div class="block m-3">
        <div
            class="spinner-grow text-success"
            id="spinner"
            role="status"
            v-if="show_loader"
        >
            <span class="sr-only">Loading...</span>
        </div>
        <div class="block-header">
            <h3 class="block-title">Customer</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <code>notifications table</code>
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
                </div>
            </form>
            <button
                type="button"
                class="btn btn-outline-info mr-1 float-right mb-2"
                onclick="exportTableToCSV('sms_notifications.csv')"
                v-if="table_flag"
            >
                <i class="fa fa-fw fa-download mr-1"></i> Download
            </button>
            <table
                class="table table-borderless table-vcenter"
                v-if="table_flag"
            >
                <thead>
                    <th class="text-center" style="width: 50px">#</th>
                    <th>Customer_code</th>
                    <th>Customer name</th>
                    <th>Total remain</th>
                    <th>Total paid</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%">
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
                                @click="getContractData(payment.customer_code)"
                            >
                                {{ payment.text }}
                            </a>
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
                                        SmsCustomerEvent(payment.customer_code)
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
                                        SmsCustomerEvent(payment.customer_code)
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
                        <div class="block-header  bg-primary-primary">
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
import Select2 from "v-select2-component";

export default {
    components: {
        Select2
    },
    data: function() {
        return {
            customer_filter: "",
            region_filter: "",
            customers: [],
            regions: [],
            payments: [],
            modal_data: [],
            table_flag: false,
            show_loader: false,
            modal_flag: false,
            customer_flag: false
        };
    },
    mounted: function() {
        this.getData();
    },
    methods: {
        getData: function() {
            var vue = this;
            axios({
                method: "get",
                url: "/notify/get"
            }).then(function(response) {
                vue.customers = response.data.customers;
                vue.regions = response.data.regions;
                vue.modal_flag = false;
            });
        },
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
        regionEvent(val) {
            this.customer_flag = false;
            this.show_loader = true;
            var vue = this;
            axios({
                method: "get",
                url: "/notify/get-region/" + val
            }).then(function(response) {
                console.log(response.data);
                if (response.data.hasOwnProperty("msg")) {
                    alert(response.data.msg);
                    vue.table_flag = false;
                } else {
                    vue.payments = response.data;
                    vue.customers = response.data;
                    $("input:checkbox.confirmation-select").prop(
                        "checked",
                        true
                    );
                    vue.table_flag = true;
                }
                vue.show_loader = false;
            });
        },
        getContractData(val) {
            this.show_loader = true;
            var vue = this;
            vue.modal_flag = true;
            const form = new FormData();
            form.append("customer_id", val);
            axios({
                method: "post",
                url: "notify/contract-data/",
                data: form
            }).then(function(response) {
                vue.show_loader = false;
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
        SmsCustomerEvent(val) {
            var vue = this;
            const form = new FormData();
            form.append("customer_code", val);
            form.append("type", "customer");
            axios({
                method: "post",
                url: "notify/status/",
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
                url: "send/notify",
                data: form
            }).then(function(response) {
                console.log(response.data);
                vue.modal_flag = false;
                $("#exampleModal").modal("hide");
            });
        },
    }
};
</script>
