import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    routes: [
        // {
        //     path: '/',
        //     name: 'home',
        //     component: Home,
        //     meta: {
        //         title: 'Home'
        //     }
        // },
    ]
});

router.beforeEach((to, from, next) => {
    next();
});

router.beforeEach((to, from, next) => {
    document.title = process.env.VUE_APP_TITLE;
    if (to.meta.title) {
        document.title += ' | ' + to.meta.title;
    }
    next();
});

export default router