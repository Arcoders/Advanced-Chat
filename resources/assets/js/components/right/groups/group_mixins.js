export const mixin = {

    // ---------------------------------------------------

    data() {
        return {
            groupName: '',
            groupAvatar: null,
            newImage: false,
            loading: false,
            group_id: this.$route.params.group_id,
            showEdit: false,
            showFriends: false,
            listFriends: [],
            selectedUsers: [],
            selectedIds: []
        }
    },

    // ---------------------------------------------------

    watch: {
        selectedUsers(value) {
            this.selectedIds = value.map(obj => obj.id);
        }
    },

    // ---------------------------------------------------

    methods: {
        // ---------------------------------------------------

        onFileChange(e) {

            let files = e.target.files || e.dataTransfer.files;
            let reader = new FileReader();

            if (!files.length) return;

            reader.onload = e => this.groupAvatar = e.target.result;

            reader.readAsDataURL(files[0]);

            this.newImage = true;

        },

        // ---------------------------------------------------

        clearAvatar() {

            this.groupAvatar = null;

           if (this.$route.name === 'edit_group') this.editGroup('image') ;

        },

        // ---------------------------------------------------

        error() {

            let verb = (this.$route.name === 'edit_group') ? 'edit' : 'added';

            this.$snotify.error(`Group can not be ${verb}`, ':(');

        },

        // ---------------------------------------------------

        done(msg) {

            this.$snotify.success(msg, 'Done');
            this.newImage = false;
            if (this.$route.name === 'add_group') this.resetForm();

        },

        // ---------------------------------------------------

        validation(data) {
            Object.entries(data).forEach( msg => this.$snotify.warning(msg[1][0], 'Validation'));
            this.newImage = false;
        },

        // ---------------------------------------------------
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

        },

        // ---------------------------------------------------

        formData() {

            let form = new FormData();

            form.append('name', this.groupName);

            if (this.newImage) form.append('avatar', this.$refs.avatarInput.files[0]);

            form.append('ids', this.selectedIds);

            return form;

        }

        // ---------------------------------------------------
    }

    // ---------------------------------------------------

};