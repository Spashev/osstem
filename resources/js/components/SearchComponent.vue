<template>
    <div class="mb-4 d-flex justify-content-center">
        <form class="d-none d-sm-inline-block" method="POST" v-on:submit.prevent="inputSearch()">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input3" name="search_input" v-model="input">
                <div class="input-group-append">
                    <span class="input-group-text bg-body border-0">
                        <i class="si si-magnifier"></i>
                    </span>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

export default {
    data: function() {
        return {
            input: '',
            data: [],
        }
    },
    mounted: function() {
        console.log('hello');
    },
    methods: {
        inputSearch: function() {
            var vue = this;
            axios({
                method: 'get',
                url: '/api/search',
                data: {
                    search_text: this.input
                }
            }).then(function(response) {
                console.log(response.data);
                vue.data = response.data;
            })
        }
    }
}
</script>
