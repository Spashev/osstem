<template>
    <div class="container">
        <button class="btn btn-info">notify</button>
    </div>
</template>

<script>
let Stomp = require('@stomp/stompjs');
export default {
    data: function() {
        return {
        };
    },

    props: ['options'],

    mounted: function() {
        console.log('hello');
        // this.queueName = _object.get(this.stomp, 'queueName', '');
        // this.uri = _object.get(this.stomp, 'uri', '');
        // this.port = _object.get(this.stomp, 'port', '');
        // if (this.queueName !== '' && this.uri !== '' && this.port !== '') {
        //     this.subscribeWS();
        // }
    },

    computed: {},

    methods: {

        commandDispatch: function(component) {},

        subscribeWS: function() {
            let vue = this;
            let wsUri = this.prepareUri(this.uri);
            this.client = new Stomp.Client({
                brokerURL: "ws://127.0.0.1:15674/ws",
                connectHeaders: {
                    login: "guest",
                    passcode: "guest",
                },
                debug: function(str) {
                    // console.log(str);
                },
                reconnectDelay: 5000,
                heartbeatIncoming: 4000,
                heartbeatOutgoing: 4000
            });

            this.client.onConnect = function(frame) {
                console.log('ws connected');
                let callback = function(message) {
                    if (message.body) {
                        let msg = JSON.parse(message.body);
                        console.log(msg);
                    }
                };
                if (vue.queueName != null) {
                    vue.client.subscribe('/amq/queue/notification', callback);
                }
            }

            this.client.onStompError = function(frame) {
                console.log(frame);
            };
            this.client.activate();
        },

        processWSMessage: function(msg) {
            switch (true) {
                case msg.hub.includes('SystemHub'):
                    this.$protocol.process({ data: msg.message });
                    break;
                case msg.hub.includes('SystemNotifyHub'):
                    jQuery.notify({
                        icon: 'fa fa-info-circle',
                        message: msg.message,
                        url: ''
                    }, {
                        element: 'body',
                        type: 'info',
                        allow_dismiss: true,
                        newest_on_top: true,
                        showProgressbar: false,
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        offset: 20,
                        spacing: 10,
                        z_index: 1033,
                        delay: 5000,
                        timer: 1000,
                        animate: {
                            enter: 'animated fadeIn',
                            exit: 'animated fadeOutDown'
                        }
                    });
                    break;
            }
        },

        prepareUri: function(uri) {
            let preparedUri = uri.split('/');
            return preparedUri[2];
        }
    }
}
</script>
