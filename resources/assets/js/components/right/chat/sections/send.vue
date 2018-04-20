<template lang="pug">

    form.send(method='POST', v-on:submit.prevent='', enctype='multipart/form-data')

        button(type='button', v-on:click='toggleModal')

            i(v-bind:class="[showModal ? 'green' : '', 'material-icons']") photo_camera

        .message
            input#msg(v-model='messageText', type='text', autocomplete='off', placeholder='Write a new message')

        button(type='button')

            i.material-icons send


</template>

<script>

    export default {

        // ----------------------------------------------

        props: ['showModal'],

        // ----------------------------------------------

        data() {
          return {
              messageText: ''
          }
        },

        // ----------------------------------------------

        methods: {

            // ----------------------------------------------

            toggleModal() {

                this.$emit('toggleModal', !this.showModal);

            }

            // ----------------------------------------------

        }

        // ----------------------------------------------

    }

</script>