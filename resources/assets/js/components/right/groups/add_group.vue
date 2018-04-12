<template lang="pug">

    .chat_groups

        .data

            router-link(to="/groups/all")
                i.material-icons arrow_back

            avatar.avatar(
            :username="groupName",
            color="#fff",
            :backgroundColor="avatarGris",
            :src="groupAvatar",
            v-bind:class="{ avatar_shadow: groupName || groupAvatar }"
            )

            h4  Add Group

            hr

        loading(v-if="loading", :normal="false")

        form(v-on:submit.prevent="", method="POST", enctype="multipart/form-data")
        .group_input


            label.upload_avatar

                button.button_upload(v-if="!groupAvatar", type='button')
                    i.material-icons backup

                button.button_upload(v-else, v-on:click="clearAvatar", type='button')
                    i.material-icons clear

                input(
                v-show="!groupAvatar",
                type="file",
                name="avatar",
                ref="avatarInput",
                v-on:change="onFileChange($event)")

            input.input_name(
            @keyup.enter="addGroup",
            name="name",
            v-model='groupName'
            type="text",
            placeholder="Group name...")

            button.button_send(@click="addGroup", type='button', v-bind:disabled="btnDisabled") Create


        multi-select(
        v-if="showFriends",
        v-model="selectedUsers",
        track-by="id",
        :multiple="true",
        label="name",
        :hide-selected="true",
        :close-on-select="false",
        :options="listFriends",
        placeholder="Select friends"
        )


</template>

<script>

    import {mixin} from './group_mixins';

    export default {

        // ---------------------------------------------------

        mixins: [mixin],

        // ---------------------------------------------------

        mounted() {
            this.allFriends();
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            resetForm() {

                this.groupAvatar = null;
                this.groupName = '';
                this.selectedUsers = [];
                this.selectedIds = [];

            },

            // ---------------------------------------------------

            addGroup() {

                if (this.btnDisabled) return;

                this.loading = true;

                this.$http.post('/groups/create', this.formData).then(res => {

                    this.loading = false;

                    (res.status === 200) ? this.done(res.data) : this.error();

                }, err => {

                    this.loading = false;

                    (err.status === 422) ? this.validation(err.data.errors) : this.error();

                });

            },

            // ---------------------------------------------------

            allFriends() {

                this.$http.get('/groups/friends').then(res => {

                    if (res.status === 200) {

                        if (res.data.length > 0) {

                            this.showFriends = true;
                            this.listFriends = res.data;

                        } else {
                            this.$snotify.warning('Find friends to add them to the group', 'Alert');
                        }

                    } else {
                        this.$snotify.warning('Could not load friend list', 'Alert');
                    }

                }, () => this.$snotify.warning('Could not load friend list', 'Alert'));

            },

            // ---------------------------------------------------

            back() {
                return this.$router.push('/groups/all');
            }

            // ---------------------------------------------------
        }

        // ---------------------------------------------------

    }

</script>