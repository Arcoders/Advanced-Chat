<template lang="pug">

    .edit_user

        form(method="POST", v-on:submit.prevent="updateProfile", enctype="multipart/form-data")

            h1 Edit information

            input.info(type='text', v-on:keyup="onInputChange($event, 'name')", v-model='user.name', placeholder='User name')

            input.info(type='text',  v-on:keyup="onInputChange($event, 'status')", v-model='user.status', placeholder='Status')

            h1 Select avatar

            label.upload_profile
                .area
                    i.material-icons.edit_i photo_camera
                input(type="file", name="fileAvatar", v-on:change="onFileChange($event, 'avatar')", ref='fileAvatar')

            h1 Select cover

            label.upload_profile
                .area
                    i.material-icons.edit_i photo_size_select_actual
                input(type='file', name='fileCover', v-on:change="onFileChange($event, 'cover')", ref='fileCover')

            button.save(v-if="save && !loading") Save

            loading(v-if="loading")


</template>

<script>

    import {mapState} from 'vuex';

    export default  {

        // ---------------------------------------------------

        data() {
          return {
              newAvatar: false,
              newCover: false,
              loading: false
          }
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            onFileChange(e, type) {

                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;

                let reader = new FileReader();

                reader.onload = (e) => {

                    if (type === 'avatar')
                    {
                        this.avatar = e.target.result;
                        this.user.avatar = this.avatar;
                        this.newAvatar = true;
                    }

                    if (type === 'cover')
                    {
                        this.cover = e.target.result;
                        this.newCover = true;
                    }

                    this.$emit('previewChanges', { type: 'images', avatar: this.avatar, cover: this.cover });

                };

                reader.readAsDataURL(files[0]);
            },

            // ---------------------------------------------------

            onInputChange() {
                this.$emit('previewChanges', { type: 'info', name: this.user.name, status: this.user.status });
            },

            // ---------------------------------------------------

            updateProfile() {

                if (!this.save) return;

                this.loading = true;

                this.$http.post('/profile/edit', this.formData).then(res => {

                    (res.status === 200) ? this.done(res.data) : this.error();

                    this.loading = false;

                }, res => {

                    (res.status === 422) ? this.validation(res.data) : this.error();

                    this.loading = false;

                });

            },

            // ---------------------------------------------------

            error() {
                this.$snotify.error('Try it later', 'Error');
            },

            // ---------------------------------------------------

            validation(data) {
                Object.entries(data.errors).forEach( msg => this.$snotify.warning(msg[1][0], 'Validation'));
            },

            // ---------------------------------------------------

            done(data) {
                this.$snotify.success(data.message, 'Done', { icon: data.user.avatar });
                this.$store.commit('updateUser', data.user);
            }

            // ---------------------------------------------------
        },

        // ---------------------------------------------------

        computed: {

            // ---------------------------------------------------

            ...mapState({
                user: (state) => state.user
            }),

            // ---------------------------------------------------

            save() {
                return (this.user.name.length >= 3 && this.user.status.length >= 5);
            },

            // ---------------------------------------------------

            formData() {

                let formData = new FormData();

                formData.append('name', this.user.name);
                formData.append('status', this.user.status);

                if (this.newAvatar) formData.append('avatar', this.$refs.fileAvatar.files[0]);
                if (this.newCover) formData.append('cover', this.$refs.fileCover.files[0]);

                return formData;

            }

            // ---------------------------------------------------

        }

        // ---------------------------------------------------

    }

</script>