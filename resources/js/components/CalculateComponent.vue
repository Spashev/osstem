<template>
<div>
    <button class="btn btn-sm btn-primary js-tooltip-enabled"  data-toggle="modal" data-target="#modal-block-large" title="show" data-original-title="Show" @click="get_data(customer_id)">
        <i class="fa fa-calculator"></i>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="modal-block-large" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Table Head Dark -->
                    <div class="block">
                        <div class="block-content  font-size-sm">
                            <table class="table table-vcenter">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Customer_id</th>
                                        <th>Name</th>
                                        <th>Contract no</th>
                                        <th>Deadline</th>
                                        <th>Amount</th>
                                        <th>Delay</th>
                                        <th>Surcharge</th>
                                        <th>Total remain</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in data" :key="index">
                                        <th class="text-center" scope="row">{{item.customer_id}}</th>
                                        <th class="text-center" scope="row">{{item.name}}</th>
                                        <th class="text-center" scope="row">{{item.contract_no}}</th>
                                        <th class="text-center" scope="row">{{item.deadline}}</th>
                                        <th class="text-center" scope="row">{{item.amount}}</th>
                                        <th class="text-center" scope="row">{{item.delay}}</th>
                                        <th class="text-center" scope="row">{{item.surcharge}}</th>
                                        <th class="text-center" scope="row">{{item.total_remain}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Table Head Dark -->
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    props: ['customer_id'],
    data: function() {
        return {
            data: [],
            phone: '',
            email: '',
            name: '',
            region: '',
            id: '',
            payment: '',
            deadline: '',
            delay: '',
            percent: '',
            total_remain: ''
        }
    },
    mounted: function() {
    },
    methods: {
        get_data(id) {
        var vue = this;
            axios({
                url: '/customer/'+id+'/peniya',
                methods: 'get'
            }).then(function(response) {
                console.log(response.data);
                vue.data = response.data;
            });
        }
    }
}
</script>