<template lang="pug">

    form.send(method='POST', v-on:submit.prevent='', enctype='multipart/form-data')

        button(type='button', v-on:click='toggleModal')

            i(v-bind:class="[showModal ? 'green' : '', 'material-icons']") photo_camera

        .message
            input#msg( @keyup.enter='sendMessage', v-model='messageText', type='text', autocomplete='off', placeholder='Write a new message')

        button(type='button', @click='sendMessage')

            i(v-bind:class="[invalidForm ? '' : 'green', 'material-icons']") send


</template>

<script>

    export default {

        // ----------------------------------------------

        props: ['user', 'showModal', 'photo', 'uploadedPhoto'],

        // ----------------------------------------------

        data() {
          return {
              messageText: '',
              chatId: window.atob(this.$route.params.chat_id)
          }
        },

        // ----------------------------------------------

        methods: {

            // ----------------------------------------------

            toggleModal() {

                this.$emit('toggleModal', !this.showModal);

            },

            // ----------------------------------------------

            sendMessage() {

                if (this.invalidForm) return;

                this.$http.post('messages/send', this.formData).then(res => {

                    (res.status === 200) ? this.resMessage('done') : this.resMessage('error');

                }, err => {

                    console.log(err);
                    this.resMessage('error')

                });

            },

            // ----------------------------------------------

            resMessage(type) {

                if (type === 'error') {

                    this.$emit('errorMessage', {
                        id: this.user.id,
                        name: this.user.name,
                        avatar: this.user.avatar,
                        photo: this.photo,
                        text: this.messageText,
                        error: true
                    });

                }

                 this.$emit('clearPhoto');
                 this.messageText = '';

            },

            // ----------------------------------------------

        },

        // ----------------------------------------------

        computed: {

            // ----------------------------------------------

            /**
             * @return {boolean}
             */

            invalidForm() {

                if (this.photo) return false;

                return (this.messageText.length < 2);

            },

            // ----------------------------------------------

            formData() {

                let formData = new FormData();

                formData.append('chatId', this.chatId);
                formData.append('messageText', this.messageText);
                formData.append('roomName', this.$route.name);

                if (this.uploadedPhoto) formData.append('photo', this.uploadedPhoto);

                return formData;

            }

            // ----------------------------------------------

        }

        // ----------------------------------------------

    }

</script>