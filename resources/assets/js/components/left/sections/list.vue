<template lang="pug">

    .list

        .contact(v-if="showPrivateList")
            p(align="center") Private

        .contact(v-else)
            p(align="center") Groups

</template>

<script>

    export default {

        // ----------------------------------------------

        props: ['showPrivateList'],

        // ----------------------------------------------

    }

</script>