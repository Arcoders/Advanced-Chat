<template lang="pug">

    .chat_groups(v-if="showEdit")

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

            h4  Edit Group

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
                        @keyup.enter="editGroup",
                        name="name",
                        v-model='groupName'
                        type="text",
                        placeholder="Group name...")

            button.button_send(@click="editGroup",type='button', v-bind:disabled="btnDisabled") Save


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
            this.getGroup();
        },

        // ---------------------------------------------------

        methods: {

            // ---------------------------------------------------

            editGroup(type = null) {

                if (this.btnDisabled) return;

                if (type === 'image' && this.newImage) return;

                let data = (type === 'image') ? { deleteImage: true } : this.formData;

                this.loading = true;

                this.$http.post(`/groups/edit/${this.group_id}`, data).then(res => {

                    this.loading = false;

                    (res.status === 200) ? this.done(res.data) : this.error();

                }, err => {

                    this.loading = false;

                    (err.status === 422) ? this.validation(err.data.errors) : this.error();

                });

            },

            // ---------------------------------------------------

            getGroup() {

                this.$http.get(`/groups/group/${this.group_id}`).then(res => {

                    if (res.status === 200) {

                        this.groupName = res.data.group.name;
                        this.groupAvatar = res.data.group.avatar;
                        this.selectedUsers = res.data.group.users;

                        this.showEdit = true;

                        if (res.data.friends.length > 0) {

                            this.showFriends = true;
                            this.listFriends = res.data.friends;

                        } else {
                            this.$snotify.warning('Find friends to add them to the group', 'Alert');
                        }

                    } else {
                        this.back();
                    }

                }, () => this.back());

            },

            // ---------------------------------------------------

            back() {
                return this.$router.push('/groups/my');
            }

            // ---------------------------------------------------
        }

        // ---------------------------------------------------

    }

</script>