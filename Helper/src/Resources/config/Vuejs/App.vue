<template>
    <b-container fluid id="app">
        <b-navbar id="top-nav-bar" fixed="top">
            <b-navbar-brand>{{ appTitle }}</b-navbar-brand>
        </b-navbar>
        <b-row id="main-content">
            <b-col sm="12">
                <router-view @showMessage="setMessage($event)"/>
                <b-alert :variant="messageVariant" class="text-center" :show="showMessage" dismissible fade>{{ message }}</b-alert>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
    name: 'App',
    data() {
        return {
            message: null,
            messageVariant: 'secondary',
            showMessage: false
        }
    },
    methods: {
        setMessage(event) {
            let timeout = !this._.isEmpty(event.timeout) ? event.timeout : 2000;
            this.message = event.message;
            this.messageVariant = !this._.isEmpty(event.variant) ? event.variant : 'secondary';
            this.showMessage = true;

            setTimeout(() => {
                this.message = '';
                this.showMessage = false;
            }, timeout);
        }
    },
    computed: {
        appTitle() {
            return process.env.VUE_APP_TITLE;
        }
    }
};
</script>

<style>
    #main-content {
        margin-top: 60px;
    }
</style>
