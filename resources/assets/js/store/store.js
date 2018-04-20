import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({

   state: {
       user: null,
       groups: null,
       friends: null,
   },

    mutations: {

       updateUser: (state, user) => state.user = user,

       updateGroups: (state, groups) => state.groups = groups,

       updateFriends: (state, friends) => state.friends = friends,

    }

});