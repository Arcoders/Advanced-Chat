<template lang="pug">

    .contact_list

        loading(v-if='loading', :normal='true')

        .contact(v-if='showPrivateList', v-for='friend in friends')

            router-link(exact-active-class='active_image', :to="chatLink(friend, 'friend')", @click.native='showRight')
                avatar.avatar(:username='friend.user.name', :src='friend.user.avatar', color='#fff')

            .preview
                .text
                    h5
                        router-link(exact-active-class='active_chat', :to="chatLink(friend, 'friend')", @click.native='showRight')
                            | {{ friend.user.name }}
                    h6
                        router-link(exact-active-class='active_message', :to="chatLink(friend, 'friend')", @click.native='showRight')
                            span(v-if="friend.msg")
                                span(v-if="friend.msg.body && friend.msg.photo")
                                    i.material-icons.photo photo
                                    | {{ friend.msg.body | truncate(20) }}
                                span(v-else-if="friend.msg.body") {{ friend.msg.body | truncate(20) }}
                                span(v-else-if="friend.msg.photo")
                                    i.material-icons.photo photo
                                    | a photo has been shared
                            span(v-else) Empty chat...

            .time
                p(v-if="friend.msg") {{ friend.msg.created_at | moment('H:mm') }}
                i.material-icons(v-else) fiber_new


        .contact(v-if='!showPrivateList', v-for='group in groups')

            router-link(exact-active-class='active_image', :to="chatLink(group, 'group')", @click.native='showRight')
                avatar.avatar(:username='group.name', :src='group.avatar', color='#fff')

            .preview
                .text
                    h5
                        router-link(exact-active-class='active_chat', :to="chatLink(group, 'group')", @click.native='showRight')
                            | {{ group.name }}
                    h6
                        router-link(exact-active-class='active_message', :to="chatLink(group, 'group')", @click.native='showRight')
                            span(v-if='group.msg')
                                span(v-if='group.msg.body && group.msg.photo')
                                    i.material-icons.photo photo
                                    | {{ group.msg.body | truncate(35) }}
                                span(v-else-if='group.msg.body') {{ group.msg.body | truncate(20) }}
                                span(v-else-if='group.msg.photo')
                                    i.material-icons.photo photo
                                    | a photo has been shared
                            span(v-else) Empty group...

            .time
                p(v-if='group.msg') {{ group.msg.created_at | moment('H:mm') }}
                i.material-icons(v-else) fiber_new

        .contact(v-if='notFoundGroups && !loading')
            p.middle groups not found

        .contact(v-if='notFoundFriends && !loading')
            p.middle friends not found

</template>

<script>

    import {mapState} from 'vuex';

    const arraySort = require('array-sort');
    const renameKeys = require('rename-keys');

    export default {

        // ----------------------------------------------

        props: ['showPrivateList'],

        // ----------------------------------------------

        data() {
            return {
                loading: false,
                groups: [],
                friends: [],
                chatsIds: [],
                errorLoad: false
            }
        },

        // ----------------------------------------------

        created() {

            this.$pusher.subscribe(`user_${this.user.id}`).bind('refreshList', () => this.chats());

            this.updateList();
            this.chats();
        },

        // ----------------------------------------------

        methods: {

            // ----------------------------------------------

            showRight() {

            },

            // ----------------------------------------------

            updateList() {

                this.$eventBus.$on('filter', data => {

                    this.groups = data.groups;
                    this.friends = data.friends;

                });

            },

            // ----------------------------------------------

            chats() {

                this.loading = true;

                this.$http.get('/list/chats').then(res => {

                    this.loading = false;

                    (res.status === 200) ? this.done(res.data) : this.errorLoad = true;

                }, err => {

                    this.loading = false;
                    this.errorLoad = true;
                    console.log(err);

                });

            },

            // ----------------------------------------------

            done(data) {

                this.groups = data.groups;

                this.$store.commit('updateGroups', arraySort(this.groups, 'msg.created_at').reverse());

                this.friends = data.friends.map(u => {

                    return renameKeys(u, key => (key === 'sender' || key === 'recipient') ? 'user' : key);

                });

                this.$store.commit('updateFriends', arraySort(this.friends, 'msg.created').reverse());

                this.chatsIds = data.chatsIds;

            },

            // ----------------------------------------------

            chatLink(chat, type) {

                if (type === 'friend') {
                    return {
                        name: 'friend_chat',
                        params: {
                            chat_id: window.btoa(chat.id),
                            friend_id: window.btoa(chat.user.id),
                            friend_name: chat.user.name
                        }
                    }
                }

                if (type === 'group') {
                    return {
                        name: 'group_chat',
                        params: {
                            chat_id: window.btoa(chat.id),
                            group_name: chat.name
                        }
                    }
                }

            }

        },

        // ----------------------------------------------

        computed: {

            // ---------------------------------------------------

            ...mapState({
                user: (state) => state.user
            }),

            // ---------------------------------------------------

            notFoundGroups() {
                return (!this.showPrivateList && this.groups && this.groups.length === 0);
            },
            // ----------------------------------------------
            notFoundFriends() {
                return (this.showPrivateList && this.friends && this.friends.length === 0);
            }
            // ----------------------------------------------

        }

        // ----------------------------------------------

    }

</script>