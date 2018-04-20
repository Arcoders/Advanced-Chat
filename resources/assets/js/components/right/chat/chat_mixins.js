
import {mapState} from 'vuex';

export const mixin = {

// ----------------------------------------------

    data() {
        return {
            showChat: false,
            chatId: window.atob(this.$route.params.chat_id),
            chatName: null,
            chatAvatar: null,
            onlineUsers:null,
            showModal: false,
            photo: null,
            messages: [],
            latest: []
        }
    },

// ----------------------------------------------

    created() {

    },

// ----------------------------------------------

    mounted() {
        this.getInformation();
    },

// ----------------------------------------------

    methods: {

        // ----------------------------------------------

        getInformation() {

            this.$http.get(this.routeType.information).then(res => {

                (res.status === 200) ? this.done(res.data) : this.$router.push('/');

            }, () => this.$router.push('/'));

        },

        // ----------------------------------------------

        done(data) {

            this.showChat = true;

            this.chatName = data.name;
            if (data.avatar) this.chatAvatar = data.avatar;

            this.latestMessages();

        },

        // ----------------------------------------------

        toggleModal(boolean) {

            this.showModal = boolean;

        },

        // ----------------------------------------------

        latestMessages() {

            this.loading = true;

            this.$http.get(`/messages/latest/${this.$route.name}/${this.chatId}`).then(res => {
                if (res.status === 200) {

                    if (res.data.length === 0) return this.welcomeMessage();

                    res.data.forEach(data => {
                        this.messages.push({
                            id: data.user.id,
                            name: data.user.name,
                            avatar: data.user.avatar,
                            photo: data.photo,
                            text: data.body,
                            time: data.created_at
                        });
                    });

                }
            }, err => {

                console.log(err);

            });

        },

        // ----------------------------------------------

        welcomeMessage() {
            this.messages.push({
                welcome: true,
                id: this.user.id,
                name: 'h i...',
                avatar: '/images/default/welcome.png',
                photo: null,
                text: 'Be the first to greet...',
                time: new Date()
            });
        },

        // ----------------------------------------------

    },

// ----------------------------------------------

    computed: {

        // ----------------------------------------------

        ...mapState({
            user: (state) => state.user
        }),

        // ----------------------------------------------

        infoById() {

            return (this.$route.params.friend_id) ? window.atob(this.$route.params.friend_id) : this.chatId;

        },

        // ----------------------------------------------

        routeType() {
            return {
                information: `/access_box/${this.$route.name}/${this.infoById}`
            }
        }

        // ----------------------------------------------

    }

// ----------------------------------------------

};