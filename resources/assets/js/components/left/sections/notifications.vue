<template lang="pug">

    .notifications

        button.mark(v-if="notifications.length > 0", @click="markAsRead") Mark all as read

        loading(v-if="loading")

        .contact(v-for="notification in notifications", v-bind:class="{ new_notification: !notification.read_at }")

            router-link(:to="{ name: 'profile', params: { profile_id: notification.data.user.id }}")

                avatar.avatar(:username="notification.data.user.name", :src="notification.data.user.avatar", color="#fff")

            .preview
                .text

                    h5
                        router-link(:to="{ name: 'profile', params: { profile_id: notification.data.user.id }}")
                            | {{ notification.data.user.name }}

                    h6 {{ notification.data.msg }}

            .time
                p {{ notification.created_at | moment('H:mm') }}


</template>

<script>

    export default {

        // ----------------------------------------------

        data() {
            return {
                loading: false,
                notifications: []
            }
        },

        // ----------------------------------------------

        mounted() {

            this.getAllNotifications();

        },

        // ----------------------------------------------

        methods: {

            // ----------------------------------------------

            getAllNotifications() {

                this.loading = true;

                this.$http.get('notifications/all').then(res => {

                    this.loading = false;

                    if (res.status === 200) {

                        this.notifications = res.data;

                        if (this.notifications.length === 0) this.$emit('updateCount', []);

                    }

                }, err => {

                    console.log(err);
                    this.loading = false;

                });

            },

            // ----------------------------------------------

            markAsRead() {

                this.loading = true;

                this.$http.get('/notifications/mark_as_read').then(res => {

                    this.loading = false;

                    if (res.status === 200) this.$emit('updateCount', []);

                }, err => {

                    console.log(err);
                    this.loading = false;

                });

            }

            // ----------------------------------------------

        }

        // ----------------------------------------------

    }

</script>