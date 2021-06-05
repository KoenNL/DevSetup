import Vue from 'vue';
import App from './App.vue';
import { BootstrapVue, BIcon } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import router from './router'
import VueSession from 'vue-session';
import VueRouter from 'vue-router';

Vue.use(BootstrapVue);
Vue.component('BIcon', BIcon);

Vue.use(VueLodash, { name: 'custom' , lodash: lodash });
Vue.use(VueRouter);
Vue.use(VueSession, {persist: true});

new Vue({
    el: '#app',
    router,
    render: h => h(App)
}).$mount('#app');