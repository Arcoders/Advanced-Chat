<template lang="pug">

    .pagination

        ul.numbers
            li
                a.prev(@click="nextPrev(source.current_page - 1)", :class="{ disable: source.current_page == 1 }") «

            li(v-for="page in pages")

                a(@click="navigate(page)", :class="{ current: source.current_page == page }") {{ page }}

            li
                a.next(@click="nextPrev(source.current_page + 1)", :class="{ disable: source.current_page == source.last_page }") »

</template>

<script>

    export default {

        // ----------------------------------------------

        props: ['source'],

        // ----------------------------------------------

        data() {
          return {
              pages: []
          }
        },

        // ----------------------------------------------

        watch: {
          source() {
              this.pages = Array.apply(null, { length: this.source.last_page }).map((value, index) => index + 1);
          }
        },

        // ----------------------------------------------

        methods: {
            // ----------------------------------------------

            nextPrev(page) {
                if (page === 0 || page === this.source.last_page + 1) return;

                this.navigate(page);
            },

            // ----------------------------------------------

            navigate(page) {
                this.$emit('navigate',page);
            }

            // ----------------------------------------------
        }

        // ----------------------------------------------

    }

</script>