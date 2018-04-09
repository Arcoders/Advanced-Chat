<template lang="pug">

    #left_app

        .menu
            router-link(to="/profile")
                avatar.avatar(:username="user.name", color="#fff", :src="user.avatar")

            router-link(to="/profile") {{ user.name }}

            .icons
                router-link(to="/groups")
                    i.material-icons person_add

                a.notifications(data-badge="7")
                    i.material-icons notifications

                a
                    i.material-icons fingerprint

        search

        .filter

            button(@click="changeList(true)", v-bind:class="{ active: privateList }") Private

            button(@click="changeList(false)", v-bind:class="{ active: !privateList }") Groups

        .contact_list

            list(:showPrivateList="privateList")

</template>

<script>

    import {mapState} from 'vuex';

    export default {

        // ---------------------------------------------------

        data() {
            return {
                privateList: true
            }
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            changeList(boolean) {
                this.privateList = boolean;
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