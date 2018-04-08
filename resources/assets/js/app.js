
import VueRouter from 'vue-router';
import Avatar from 'vue-avatar';
import VueResource from 'vue-resource';
import Snotify from 'vue-snotify';

window.Vue = require('vue');
window.Vue.use(VueRouter);
window.Vue.use(VueResource);
Vue.use(Snotify);



const CSRF = document.getElementById('csrf-token').getAttribute('content');

Vue.http.headers.common['X-CSRF-TOKEN'] = CSRF;

import Global from './components/global.vue';
import Left from './components/left/left.vue';
import Welcome from './components/right/welcome.vue';
import Profile from './components/right/profile/profile.vue';
import EditProfile from './components/right/profile/edit_profile.vue';
import Loading from './components/spinner/loading.vue';
import Friendship from './components/right/friends/friendship.vue';

import {store} from './store/store';

Vue.component('global', Global);
Vue.component('left', Left);
Vue.component('avatar', Avatar);
Vue.component('loading', Loading);
Vue.component('friendship', Friendship);

const router = new VueRouter({
   routes: [
       { path: '/', component: Welcome },
       {
           path: '/profile/:profileId?', component: Profile, name: 'profile',
           children: [
               { path: 'edit', component: EditProfile, name: 'editProfile' }
           ]
       },
       { path: '/*', component: Welcome }
   ]
});


new Vue({
   el: '#app',
    router,
    store
});