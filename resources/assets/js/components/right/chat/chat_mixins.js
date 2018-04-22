
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

        this.pushRealTimeMessage();

    },

// ----------------------------------------------

    mounted() {

        this.getInformation();

    },

// ----------------------------------------------

    watch: {
        messages() {

            this.scrollDown();

        }
    },

// ----------------------------------------------

    methods: {

        // ----------------------------------------------

        pushRealTimeMessage() {

            this.$pusher.subscribe(this.dataType.newMessage).bind('newMessage', (data) => {

                if (this.messages[0]['welcome']) this.messages.shift();

                this.messages.push({
                    id: data.user.id,
                    name: data.user.name,
                    avatar: data.user.avatar,
                    photo: data.message.photo,
                    text: data.message.body,
                    time: data.message.created_at
                });

            });

        },

        // ----------------------------------------------

        pushErrorMessage(data) {

            this.messages.push(data);

        },

        // ----------------------------------------------

        onFileChange(e) {

            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;

            let reader = new FileReader();

            reader.onload = (e) => {

                this.photo = e.target.result;
                document.getElementById("msg").focus();

            };

            reader.readAsDataURL(files[0]);

        },

        // ----------------------------------------------

        getInformation() {

            this.$http.get(this.dataType.information).then(res => {

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

                    res.data.reverse();

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

        hideModal() {

            this.photo = null;
            this.showModal = false;

        },

        // ----------------------------------------------

        scrollDown() {

            window.setTimeout( () => {

                let elem = window.document.getElementById('chat');
                elem.scrollTop = elem.scrollHeight;

            }, 500);

        }

        // ----------------------------------------------

    },

// ----------------------------------------------

    computed: {

        // ----------------------------------------------

        ...mapState({
            user: (state) => state.user
        }),

        // ----------------------------------------------

        uploadedPhoto() {

            if (this.photo) return this.$refs.photoInput.files[0];

        },

        // ----------------------------------------------

        infoById() {

            return (this.$route.params.friend_id) ? window.atob(this.$route.params.friend_id) : this.chatId;

        },

        // ----------------------------------------------

        dataType() {

            return {

                information: `/access_box/${this.$route.name}/${this.infoById}`,

                newMessage: `${this.$route.name}-${this.chatId}`

            }

        }

        // ----------------------------------------------

    }

// ----------------------------------------------

};