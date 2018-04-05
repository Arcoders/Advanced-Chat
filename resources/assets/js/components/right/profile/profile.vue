<template lang="pug">
        .right(v-if="showProfile")
            .head

                h1
                    router-link(v-if="pathProfile && user.id == userInfo.id", to="/profile/edit")
                        i.material-icons edit_location

                    router-link(v-else-if="pathEdit", to="/profile")
                        i.material-icons arrow_back

                    router-link(v-else, to="/profile", @click.native="setUserInfo")
                        i.material-icons arrow_back

                .info
                    p Profile
                    p.name {{ userInfo.name }}

            .profile_box
                .information
                    .widget(v-bind:class="{ widget_100: profileId }")
                        .cover
                            img(:src="userInfo.cover")

                            // friendship

                        avatar.photo(:username="userInfo.name", color="#fff", :src="userInfo.avatar", :size="100")

                        h1 {{ userInfo.name }}
                        h2 {{ userInfo.status }}

                    .users(v-if="!profileId")

                        router-view(@previewChanges="previewInfo")

                        div(v-if="pathProfile")

                            .list(v-for="(user, i) in users")

                                avatar.image(:username="user.name", color="#fff", :src="user.avatar", :size="50")
                                button.name(@click="userInfo = users[i]", v-bind:class="{ current_user: user.id ==  userInfo.id}")
                                    | {{ user.name }}

                            .list(v-if="!records")

                                avatar.image(username="!", color="#fff", :size="50", backgroundColor="#e57373")
                                button.name(@click="getUsers") You are the first user

                            .list(v-if="records")

                                button.random(@click="getUsers")
                                    i.material-icons cached


</template>

<script>

    import {mapState} from 'vuex';

    export default {

        // ---------------------------------------------------

        data() {
          return {
              users: null,
              records: false,
              userInfo: null,
              showProfile: false,
              profileId: this.$route.params.profileId
          }
        },

        // ---------------------------------------------------

        mounted() {
          this.getProfile();
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            setUserInfo() {

                if (this.profileId) return;

                this.userInfo = {
                    name: this.user.name,
                    status: this.user.status,
                    id: this.user.id,
                    avatar: this.user.avatar,
                    cover: this.checkCover(this.user.cover)
                };

            },

            // ---------------------------------------------------

            getUsers() {

                this.$http.get('/profile/users').then(res => {

                    if (res.status === 200) {
                        this.users = res.data;
                        if (res.data.length > 0) this.records = true;
                        this.showProfile = true;
                    }

                });

            },

            // ---------------------------------------------------

            getProfile() {

                this.setUserInfo();

                if (this.profileId) {

                    if (!isNaN(this.profileId)) return this.getProfileById(this.profileId);
                    this.$router.push('/profile');

                } else {
                    this.getUsers();
                }

            },

            // ---------------------------------------------------

            getProfileById(id) {

                this.$http.get(`/profile/user/${id}`).then(res => {

                    if (res.status === 200) {

                        this.userInfo = {
                            name: res.data.name,
                            status: res.data.status,
                            id: res.data.id,
                            avatar: res.data.avatar,
                            cover: this.checkCover(res.data.cover)
                        };

                        this.showProfile = true;

                    } else {
                        this.router.push('/profile');
                    }

                }, () => {
                    this.$router.push('/profile');
                });

            },

            // ---------------------------------------------------

            checkCover(cover) {
                return (cover) ? cover : '/images/default/default_cover.jpg'
            },

            // ---------------------------------------------------

            previewInfo(data) {

                if (data.type === 'images')
                {
                    if (data.avatar) this.userInfo.avatar = data.avatar;
                    if (data.cover) this.userInfo.cover = data.cover;
                }

                if (data.type === 'info')
                {
                    this.userInfo.name = data.name;
                    this.userInfo.status = data.status;
                }

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

            pathProfile() {
                return (this.$route.name === 'profile');
            },

            // ---------------------------------------------------

            pathEdit() {
                return (this.$route.name === 'editProfile')
            }

            // ---------------------------------------------------

        }

        // ---------------------------------------------------

    }

</script>