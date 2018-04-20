<template lang="pug">

    .search_container
        .search
            i.material-icons search
            input(type='text', placeholder='Search...', v-model="name", v-on:keyup="filter")

</template>

<script>

    import {mapState} from 'vuex';

    export default {

        // ----------------------------------------------

        data() {
            return {
                name: ''
            }
        },

        // ----------------------------------------------

        methods: {
            // ----------------------------------------------

            filter() {

                let type = 'chat';

                let friends = this.friends.filter(p => p.user.name.match(new RegExp(this.name, 'i')));

                let groups = this.groups.filter(g => g.name.match(new RegExp(this.name, 'i')));

                type = (groups.length > 0 && groups.length > friends.length) ? 'group' : 'chat';

                if (this.name === '') type = 'chat';

                this.$eventBus.$emit('filter', {type, friends, groups} );

            }

            // ----------------------------------------------
        },

        // ----------------------------------------------

        computed: mapState({

           friends: state => state.friends,

           groups: state => state.groups

        }),

        // ----------------------------------------------

    }

</script>