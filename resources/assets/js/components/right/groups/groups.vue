<template lang="pug">

    .manage_groups

        .head

            h1
                i.material-icons group_add

            .info
                p Groups
                p.name You can create public chat rooms for all users...


        .groups_box

            .content

                .groups(v-if="$route.name == 'groups'")

                    .group
                        router-link(to="groups/add") Add Groups

                    .group
                        router-link(to="groups/all") My Groups

                router-view

</template>
