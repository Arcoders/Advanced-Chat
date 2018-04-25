<template lang="pug">

    include chat_box.pug

</template>

<script>

    import {mixin} from './chat_mixins';

    export default {

        // ----------------------------------------------

        mixins: [mixin],

        // ----------------------------------------------

    }

</script>