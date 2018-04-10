export const mixin = {

    // ---------------------------------------------------

    data() {
        return {
            groupName: '',
            groupAvatar: null,
            loading: false,
            group_id: this.$route.params.group_id,
            showEdit: false,
            showFriends: false,
            listFriends: null,
            selectedUsers: null,
            selectedIds: null
        }
    },

    // ---------------------------------------------------

    watch: {
        selectedUsers(value) {
            this.selectedIds = value.map(obj => obj.id);
        }
    },

    // ---------------------------------------------------

    computed: {
        // ---------------------------------------------------

        btnDisabled() {
            return (this.groupName.length < 3);
        },

        // ---------------------------------------------------

        avatarGris() {
            return (this.groupName === '' ) ? '#EEEEEE' : '';
        }

        // ---------------------------------------------------
    }

    // ---------------------------------------------------

};