<template lang="pug">

    .bubble

        div(v-for='message_user in messages',
        v-bind:class="['line', checkId(message_user.id) ? 'user' : 'friend']")

            div(v-bind:class="checkId(message_user.id) ? 'user_mouth' : 'friend_mouth'")

                avatar(:username='message_user.name',
                color='#fff',
                :size='45',
                :src='message_user.avatar',
                v-bind:class="checkId(message_user.id) ? 'user_img' : 'friend_img'")

            .content
                div(v-if='message_user.photo')
                    img(:src='message_user.photo')
                p  {{ message_user.text }}

            .time(v-if='!message_user.error') {{ message_user.time | moment("from", "now") }}
            .time(v-else)
                i.material-icons.error error

</template>

<script>

    export default {

        // ----------------------------------------------

        props: ['messages', 'user'],

        // ----------------------------------------------

        methods: {

            // ----------------------------------------------

            checkId(message_user_id) {

                return (this.user.id === message_user_id);

            },

            // ----------------------------------------------

        }

        // ----------------------------------------------

    }

</script>