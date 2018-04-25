<template lang="pug">

    #left_app

        .menu
            router-link(to="/profile", @click.native='showRight')
                avatar.avatar(:username="user.name", color="#fff", :src="user.avatar")

            router-link(to="/profile", @click.native='showRight') {{ user.name | truncate(15)}}

            .icons
                router-link(to="/groups", @click.native='showRight')
                    i.material-icons person_add

                a.notifications(:data-badge="totalNotifications", @click="toggleListNotifications")
                    i.material-icons notifications

                a(v-on:click="logout")
                    i(v-bind:class="[logoutError ? 'error' : '', 'material-icons']") fingerprint

        search

        .filter

            button(@click="changeList(true)", v-bind:class="{ active: privateList }") Private

            button(@click="changeList(false)", v-bind:class="{ active: !privateList }") Groups

        .contact_list(v-if="showNotifications")

            notifications(v-on:updateCount="totalNotifications = 0, showNotifications = false")

        .contact_list(v-show="!showNotifications")

            list(:showPrivateList="privateList")

</template>

<style scoped>
    .contact_list {
        height: calc(95vh - 155px);
        background-color: #ffffff;
    }
</style>

<script>

    import {mapState} from 'vuex';
    import {mixin} from '../../style';

    export default {

        // ---------------------------------------------------

        data() {
            return {
                privateList: true,
                logoutError: null,
                totalNotifications: 0,
                showNotifications: false
            }
        },

        // ---------------------------------------------------

        mixins: [mixin],

        // ---------------------------------------------------

        created() {

            this.$eventBus.$on('filter', data => this.listType(data.type));

            this.$pusher.subscribe(`notification_${this.user.id}`).bind('updateCount', () => this.getTotalNotifications());

            this.$pusher.subscribe(`user_${this.user.id}`).bind('refreshList', data => this.listType(data.type));

        },

        // ---------------------------------------------------

        mounted() {

            this.getTotalNotifications();

        },

        // ---------------------------------------------------

        methods: {

            // ---------------------------------------------------

            toggleListNotifications() {

                this.showNotifications = !this.showNotifications

            },

            // ---------------------------------------------------

            changeList(boolean) {

                this.privateList = boolean;
                this.showNotifications = false;

            },

            // ---------------------------------------------------

            listType(type) {

                (type === 'chat') ? this.changeList(true) : this.changeList(false);

            },

            // ---------------------------------------------------

            logout() {

                this.$http.post('/logout').then(res => {

                    (res.status === 200) ? window.location.href = '/' : this.logoutError = true;

                }, err => {

                    this.logoutError = true;
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            getTotalNotifications() {

                this.$http.get('/notifications/count').then(res => {

                    if (res.status === 200) {

                        this.totalNotifications = res.data;
                        this.showNotifications = false;

                    }

                }, err => console.log(err));

            }

            // ---------------------------------------------------
        },

        // ---------------------------------------------------

        computed: mapState({
            user: (state) => state.user
        })

        // ---------------------------------------------------

    }

</script>