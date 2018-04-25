<template lang="pug">

    .wrap

        router-link.navigate(to='#', @click.native='updateStyles')
            i.material-icons arrow_back

        .left
            left

        .right
            router-view(:key='$route.fullPath')

        vue-snotify
</template>

<script>

    import {mixin} from '../style';

    export default {

        // ---------------------------------------------------

        props: ['auth_user'],

        // ---------------------------------------------------

        mixins: [mixin],

        // ---------------------------------------------------

        created() {
            this.$store.commit('updateUser', this.auth_user);
        },

        // ---------------------------------------------------

        mounted() {
            this.resetStyle();
        }

        // ---------------------------------------------------

    }

</script>