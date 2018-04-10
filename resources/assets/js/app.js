
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
import Search from './components/left/sections/search.vue';
import List from './components/left/sections/list.vue';
import Groups from './components/right/groups/groups.vue';
import MyGroups from './components/right/groups/my_groups.vue';
import Pagination from './components/pagination/paginate.vue';
import EditGroup from './components/right/groups/edit_group.vue';
import Multiselect from 'vue-multiselect';

import {store} from './store/store';

Vue.component('global', Global);
Vue.component('left', Left);
Vue.component('avatar', Avatar);
Vue.component('loading', Loading);
Vue.component('friendship', Friendship);
Vue.component('search', Search);
Vue.component('list', List);
Vue.component('paginate', Pagination);
Vue.component('multi-select', Multiselect);

const router = new VueRouter({
   routes: [
       { path: '/', component: Welcome },
       {
           path: '/profile/:profileId?', component: Profile, name: 'profile',
           children: [
               { path: 'edit', component: EditProfile, name: 'editProfile' }
           ]
       },
       {
           path: '/groups', component: Groups, name: 'groups',
           children: [
               { path: 'my', component: MyGroups },
               { path: 'edit/:group_id/:group_name', component: EditGroup, name: 'edit_group' }
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