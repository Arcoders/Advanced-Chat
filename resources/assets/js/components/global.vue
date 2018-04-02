<template lang="pug">

    .wrap

        .left
            left

        .right
            router-view
</template>

<script>

    export default {

        // ---------------------------------------------------

        props: ['auth_user'],

        // ---------------------------------------------------

        created() {
            this.$store.commit('updateUser', this.auth_user);
        },

        // ---------------------------------------------------

        mounted() {
            console.log('Global component ok!');
        }

        // ---------------------------------------------------

    }

</script>