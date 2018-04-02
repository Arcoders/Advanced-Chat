
import VueRouter from 'vue-router';

window.Vue = require('vue');
window.Vue.use(VueRouter);

import Avatar from 'vue-avatar';
import Global from './components/global.vue';
import Left from './components/left/left.vue';
import Welcome from './components/right/welcome.vue';

import {store} from './store/store';

Vue.component('global', Global);
Vue.component('left', Left);
Vue.component('avatar', Avatar);

const router = new VueRouter({
   routes: [
       { path: '/', component: Welcome },
       { path: '/*', component: Welcome }
   ]
});


new Vue({
   el: '#app',
    router,
    store
});