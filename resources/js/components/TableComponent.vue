<template>
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Bordered Table</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <code>.table-bordered</code>
                </div>
            </div>
        </div>
        <div class="block-content">
            {{ payments }}
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">
                            Access
                        </th>
                        <th class="text-center" style="width: 100px;">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center" scope="row">1</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php"
                                >Danielle Jones</a
                            >
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-primary">Personal</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">2</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php"
                                >Jeffrey Shaw</a
                            >
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-info">Business</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">3</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php"
                                >Lisa Jenkins</a
                            >
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-info">Business</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">4</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php">Susan Day</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-success">VIP</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">5</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php">Susan Day</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-danger">Disabled</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" scope="row">6</th>
                        <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php">Carol Ray</a>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-info">Business</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Edit Client"
                                >
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-light js-tooltip-enabled"
                                    data-toggle="tooltip"
                                    title=""
                                    data-original-title="Remove Client"
                                >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Select2 from "v-select2-component";

export default {
    props: ["payments"],
    components: {
        DatePicker,
        Select2
    },
    data: function() {
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
            myValue: "",
            myOptions: [],
            contract_flag: false
        };
    },
    mounted: function() {
        console.log("hz");
    },
    methods: {}
};
</script>
