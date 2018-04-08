<template lang="pug">

    .friends
        div(v-if="!loading")
            .style_friend.add_friend

                button(v-if="status == 'not_friends'", @click='addFriend')
                    i.material-icons person_add

                button.green(v-if="status == 'pending'", @click='acceptFriend')
                    i.material-icons done_all

                button.green(v-if="status == 'waiting'")
                    i.material-icons near_me

                button.accepted(v-if="status == 'friends'")
                    i.material-icons favorite

            .style_friend.delete_friend(v-if="status != 'not_friends'")

                button.orange(@click="rejectFriendship")
                    i.material-icons clear

        loading(v-if="loading", :normal="false")

</template>

<script>

    export default {

        // ---------------------------------------------------

        props: ['profileId', 'userId'],

        // ---------------------------------------------------

        data() {

            return { status: '',  loading: false }

        },

        // ---------------------------------------------------

        watch: {

            profileId() { this.relationShipStatus() }

        },

        // ---------------------------------------------------

        mounted() {
          this.relationShipStatus();
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            relationShipStatus() {

                this.loading = true;

                this.$http.get(`/friendship/check/${this.profileId}`).then(res => {

                    this.loading = false;

                    if (res.status === 200) this.status = res.body;

                }, (err) => {

                    this.loading = false;
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            addFriend() {

                this.loading = true;

                this.$http.post(`/friendship/add/${this.profileId}`).then(res => {

                    this.loading = false;

                    if (res.status === 200) this.status = res.body;

                }, (err) => {

                    this.loading = false;
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            acceptFriend() {

                this.loading = true;

                this.$http.patch(`/friendship/accept/${this.profileId}`).then(res => {

                    this.loading = false;

                    if (res.status === 200) this.status = res.body;

                }, (err) => {

                    this.loading = false;
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            rejectFriendship() {

                this.loading = true;

                this.$http.delete(`/friendship/reject/${this.profileId}`).then(res => {

                    this.loading = false;

                    if (res.status === 200) this.status = res.body;

                }, (err) => {

                    this.loading = false;
                    console.log(err);

                });

            }

            // ---------------------------------------------------
        },

        // ---------------------------------------------------

    }

</script>