<template lang="pug">

    .welcome
        img(:src="image")
        p {{ text }}

</template>

<script>

    export default {

        // ---------------------------------------------------

        data() {
            return {
                image: 'images/default/udemy.png',
                text: 'Real-time chat app'
            }
        }

        // ---------------------------------------------------

    }

</script>