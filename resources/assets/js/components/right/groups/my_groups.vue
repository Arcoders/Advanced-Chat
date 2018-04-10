<template lang="pug">

    .chat_groups

        loading(v-if="loading", :normal="false")

        .data

            router-link(to="/groups")
                i.material-icons arrow_back

            h4 My Groups

                router-link(to="/groups/add")
                    i.add.material-icons add

            hr

        table

            thead
                tr
                    th Avatar
                    th Name
                    th Edit
                    th Status

            tbody

                tr.data(v-for='(group, index) in groups')

                    td
                        avatar.table_avatar(:size="45", :username="group.name", :src="group.avatar", color="#fff")

                    td {{ group.name }}

                    td
                        router-link(:to="editLink(group)")
                            i.material-icons.green mode_edit

                    td
                        button.format(v-if="!group.deleted_at", v-on:click="changeStatus(group.id, 'delete')")
                            i.material-icons.red delete

                        button.format(v-else, v-on:click="changeStatus(group.id, 'restore')")
                            i.material-icons.orange settings_backup_restore

                tr(v-if="notFound")
                    td(colspan='4') No records found, please
                        router-link.green.link_add(to="/groups/add") Add Groups

                tr(v-if='errorLoad')
                    td(colspan='4')
                        p.red Sorry :( records could not be loaded

        paginate(:source="pagination", @navigate="changePage")

</template>

<script>

    export default {

        // ---------------------------------------------------

        data() {
            return {
                loading : false,
                groups: null,
                pagination: {},
                actualPage: null,
                notFound: false,
                errorLoad: false
            }
        },

        // ---------------------------------------------------

        mounted() {
          this.getGroups('/groups/all');
        },

        // ---------------------------------------------------

        methods: {
            // ---------------------------------------------------

            getGroups(url) {

                this.loading = true;

                this.$http.get(url).then(res => {

                    this.loading = false;

                    if (res.status === 200) {

                        this.groups = res.data.data;
                        this.pagination = res.data;
                        this.actualPage = this.pagination.current_page;

                        if (!this.groups || this.groups.length === 0) this.notFound = true;

                    }

                }, err => {

                    this.loading = false;
                    this.errorLoad = true;
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            changePage(number) {
                this.getGroups(`/groups/all?page=${number}`)
            },

            // ---------------------------------------------------

            changeStatus(group_id, type) {

                this.loading = true;

                this.$http.patch(`/groups/${type}/${group_id}`).then(res => {

                    this.loading = false;

                    if (res.status === 200) {

                        this.$snotify.success(res.data, 'Done');

                        this.changePage(this.actualPage);

                    } else {

                        this.error();

                    }

                }, err => {

                    this.loading = false;
                    this.error();
                    console.log(err);

                });

            },

            // ---------------------------------------------------

            error() {
                this.$snotify.error('An error has occurred', ':(');
            },

            // ---------------------------------------------------

            editLink(group) {

                return {
                    name: 'edit_group',
                    params: {
                        group_id: group.id,
                        group_name: group.name
                    }
                }

            }

            // ---------------------------------------------------
        }

        // ---------------------------------------------------

    }

</script>