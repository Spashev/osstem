<template>
    <div>
        <vue-dropzone 
            id="drop2" 
            :options="dropOptions" 
            @vdropzone-complete="afterComplete"
            @change="progress_flag = true"
        >
        </vue-dropzone>
        <div class="mt-5">
            <k-progress 
                v-if = "progress_flag"
                type="line"
                :percent="progress_precent" 
                :line-height="20"
                :color="['#f5af19', '#f12711', '#9254de', '#40a9ff', '#5cdbd3']" 
                :color-flow="true"
            />
        </div>
    </div>
</template>

<script>
import KProgress from 'k-progress';
import vueDropzone from "vue2-dropzone";
export default {
    components: {
        KProgress,
        vueDropzone
    },
    mounted: function() {
    },
    data: function() {
        return  {
            dropOptions: {
                url: "/excel/upload",
                maxFilesize: 200, // MB
                maxFiles: 20,
                addRemoveLinks: true,
            },
            progress_flag: false,
            progress_precent: 0,
        }
    },
    methods: {
        afterComplete(file) {
            // console.log(file);
            this.progress_bar_status()
            this.progress_flag = true;
        },
        progress_bar_status()
        {
            var vue = this;
            axios({
                url: '/progress-bar-status?type=excel',
                methods: 'get'
            }).then(function(response) {
                // console.log(response.data);
                if (response.data.pregress_bar == 'stop'){
                    vue.progress_precent = 100;
                } else {
                    vue.progress_precent = response.data.pregress_bar;
                    setTimeout(vue.progress_bar_status, 5000)
                }
            });
        }
    },
};
</script>