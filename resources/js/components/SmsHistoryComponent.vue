<template>
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Customer</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <code>notifications</code>
                </div>
            </div>
        </div>
        <div class="block-content">
            <form method="POST">
                <div class="form-group form-row row text-center">
                    <div class="col-sm-4">
                        <label>Customers</label>
                        <Select2
                            id="customers"
                            name="customers"
                            placeholder="Select customers"
                            v-model="customer_filter"
                            :options="customers"
                            @change="customerEvent($event)"
                        />
                    </div>
                    <div class="col-sm-4">
                        <label>Phone</label>
                        <Select2
                            id="phone"
                            name="phone"
                            placeholder="Select phone"
                            v-model="phone_filter"
                            :options="phones"
                            @change="phoneEvent($event)"
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
                    <th>Contract no</th>
                    <th>Customer name</th>
                    <th>Amount</th>
                    <th>Phone_number</th>
                    <th>Created_at</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%">
                        <div
                            class="custom-control custom-checkbox custom-checkbox-rounded-circle custom-control-success mb-1"
                        >
                            Sms status
                        </div>
                    </th>
                </thead>
                <tbody>
                    <tr v-for="item in payments" v-bind:key="item.id">
                        <th class="text-center" scope="row">
                            {{ item.id }}
                        </th>
                        <td class="font-w600 font-size-sm">
                            <a href="javascript:void(0);">{{
                                item.contract_no
                            }}</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <a href="javascript:void(0);">
                                {{ item.customer_name }}
                            </a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span>{{ item.amount }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span>{{ item.phone_number }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span>{{
                                item.created_at | truncate(10, " ")
                            }}</span>
                        </td>
                        <td class="text-center">
                            <div
                                class="custom-control custom-checkbox custom-control-success custom-control-lg mb-1"
                            >
                                <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="example-cb-custom-square2"
                                    name="example-cb-custom-square2"
                                    checked=""
                                    disabled
                                />
                                <label
                                    class="custom-control-label"
                                    for="example-cb-custom-square2"
                                    >sms</label
                                >
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="m-3">
                <pagination
                    :data="pagination_data"
                    @pagination-change-page="getData"
                ></pagination>
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
            phone_filter: "",
            customers: [],
            payments: [],
            phones: [],
            table_flag: false,
            show_loader: false,
            modal_flag: false,
            pagination_data: {}
        };
    },
    mounted: function() {
        this.getData();
    },
    methods: {
        getData: function(page = 1) {
            var vue = this;
            axios({
                method: "get",
                url: "/send-sms/history?page=" + page
            }).then(function(response) {
                console.log(response.data);
                vue.customers = response.data.customers;
                vue.phones = response.data.phones;
                vue.pagination_data = response.data.paginate;
                vue.payments = response.data.paginate.data;
                vue.table_flag = true;
                vue.modal_flag = false;
            });
        },

        customerEvent(val) {
            this.show_loader = true;
            var vue = this;
            axios({
                method: "get",
                url: "/sms/history/" + val
            }).then(function(response) {
                console.log(response.data);
                if (response.data.paginate.hasOwnProperty("msg")) {
                    alert(response.data.paginate.msg);
                    vue.table_flag = false;
                } else {
                    vue.payments = response.data.paginate;
                    vue.table_flag = true;
                }
                vue.show_loader = false;
            });
        },

        phoneEvent(val) {
            this.show_loader = true;
            var vue = this;
            axios({
                method: "get",
                url: "/sms/history-phone/" + val
            }).then(function(response) {
                console.log(response.data);
                if (response.data.hasOwnProperty("msg")) {
                    alert(response.data.msg);
                    vue.table_flag = false;
                } else {
                    vue.payments = response.data.paginate;
                    vue.table_flag = true;
                }
                vue.show_loader = false;
            });
        }
    }
};
</script>
